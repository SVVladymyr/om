<?php

namespace App\Http\Controllers;

use App\Order;
use App\Specification;
use App\Product;
use App\Description;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\SetStatusesRequest;
use App\Http\Requests\OrderFilteringRequest;
use Illuminate\Support\Facades\DB;

use App\MyClasses\CustomPaginator;

use App\Client;
use Carbon\Carbon;

class OrderController extends Controller//refactor todo
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function user_index(OrderFilteringRequest $request)//pagination. desc or asc by date
    {
            if(Auth::user()->isCompanyAdmin()) {
                $clients = Client::pluck('name', 'id')->all();          

            }elseif(Auth::user()->isClientAdmin()) {
                $clients = Auth::user()->employer->network()->pluck('name', 'id')->all();          

            }elseif(Auth::user()->isManager()) {
                $roots = Auth::user()->clients;

                $collect_network = collect();

                foreach ($roots as $root) {
                    $collect_network = $collect_network->merge($root->network);
                }

                $clients = $collect_network->pluck('name', 'id')->all();

            }elseif(Auth::user()->isSublevel()) {
                $clients = Auth::user()->subject->expand_network()->pluck('name', 'id')->all();
                $clients[Auth::user()->subject->id] = Auth::user()->subject->name;//add users subject

            }elseif(Auth::user()->isConsumer()) {
                $clients = Auth::user()->subject()->pluck('name', 'id')->all();
            }

            if(!empty($request->input())) {
                $filters = $request->all();
                unset($filters['_token']);
                $date_filters = array_filter($filters, 'is_string');
                $status_clients_filters = array_filter($filters, 'is_array');
                $filters = array_merge($date_filters, $status_clients_filters);

            }else {
                $filters = [];
            }

            if(empty($filters) &&
                empty($request->input() &&
                session()->has('filters'))) {
                $filters = session()->get('filters');
            }else {
                session()->forget('filters');
            }

            if(empty($filters)) {
                $orders = Order::whereIn('client_id', array_keys($clients))
                                ->get()->sortByDesc('created_at');

            }else {
                session(['filters' => $filters]);

                $order = $this->make_filter_query($filters, $clients);

                $orders = $order->get()->sortByDesc('created_at');
            }
            
        if($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                $this->show_sublevel_confirmation($order);
            }
        }

        $orders = CustomPaginator::paginate($orders, 20)->setPath(route('user_orders'));
        
        return view('orders.index', compact('orders', 'clients'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        $this->authorize('orders', $client);

        $orders = Order::where('client_id', '=', $client->id )->get()->sortByDesc('created_at');

        if($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                $this->show_sublevel_confirmation($order);
            }
        }else {
            session()->flash('message', 'No orders yet');
        }

        $orders = CustomPaginator::paginate($orders, 20)->setPath(route('orders', [$client->id]));

        return view('orders.index', compact('orders', 'client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Client $client)
    {
        $this->authorize('create_order', $client);

        $limits= $client->limits;

        $specification = $client->real_specification;

        if($specification != null) {
            $products = Specification::products($specification);
            
        }else {
            return back()->with('message', 'Cant create order, client has no specification');
        }

        if($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product->current_value = $limits
                                            ->where('limitable_id', $product->product_id)
                                            ->where('limitable_type', "App\Product")
                                            ->pluck('current_value')
                                            ->first();
            }
            
        }else {
            return back()->with('message', 'Cant create order, fill specification');
        }          

        return view('orders.create', compact('client', 'limits', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request, Client $client)
    {
        $this->authorize('create_order', $client);

        if(array_sum($request['amounts']) === 0) {
            return back()->withInput()->with('message', 'Cant create empty order');
        }

        $amounts = array_filter($request['amounts'], "is_numeric");//amount values are not null

        $specification = $client->real_specification;

        $begin = (int)$specification->order_begin;
        $end = (int)$specification->order_end;

        if(!$this->check_order_window($begin, $end)) {
            return back()->with('message', 'Sorry, not today');
        }

        if($specification != null) {
            $products = Specification::products($specification);

        }else {
            return back()->withInput()->with('message', 'Cant create order, create and fill specification');
        }

        if($products->isEmpty()) {
            return back()->withInput()->with('message', 'Cant create order, fill specification');
        }

        $products = $products->whereIn('product_id', array_keys($amounts));

        foreach ($products as $product) {
            $product->amount = $amounts[$product->product_id];
            $product->total = $product->amount * $product->price;
        }

        $order_sum = array_sum($products->pluck('total')->all());

        $limits= $client->limits->where('active', 1);

        $exceed_messages = $this->check_limits_exceedence($limits, $order_sum, $products);

        if(!empty($exceed_messages)) {
            $exceed_messages = array_unique($exceed_messages);
            $message = 'Cant create order. Following limits exceeded: ' . implode(', ', $exceed_messages);
            return back()->withInput()->with('message', $message);
        }

        DB::transaction(function() use($client, $products, $amounts, $limits, $order_sum){
            $new_order = Order::create([
                'client_id' => $client->id,
                'expected_delivery_date' => request('expected_delivery_date'),
                'delivery_date' => request('delivery_date'),
                'customer_id' => Auth::user()->id,
                'sum' => $order_sum,
            ]);

            $data = [];

            foreach ($amounts as $product => $amount) {
            $data[] = array('order_id' => $new_order->id, 
                            'product_id' => $product, 
                            'amount' => $amount, 
                            'price' => $products
                                        ->where('product_id', '=', $product)
                                        ->pluck('price')
                                        ->first()
                            );
            }

            DB::table('order_product')
                ->insert($data);

            if($limits->isNotEmpty()) {
                foreach ($limits as $limit) {
                    $limit->save();
                }
            }   
                
        });

        session()->flash('message', 'New order has been created');
        
        return redirect()->route('orders', [$client]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order_items = DB::table('order_product')
                            ->where('order_id', $order->id)
                            ->get();

        $product_ids = $order_items
                        ->pluck('product_id')
                        ->all();


        $products = Product::whereIn('product_id', $product_ids)->get();

        foreach ($products as $product) {
            $product->amount = $order_items
                                ->where('product_id', '=', $product->product_id)
                                ->pluck('amount')
                                ->first();
            $product->price = $order_items
                                ->where('product_id', '=', $product->product_id)
                                ->pluck('price')
                                ->first();
        }

        return view('orders.show', compact('order', 'products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)//check status and role
    {
        $this->authorize('update', $order);

        if((Auth::user()->isConsumer() && 
                ($order->status_id != 1 || strripos($order->sublevel_confirm, 'true') !== false)) || 
            (Auth::user()->isSublevel() && $order->status_id != 1) ||
            (Auth::user()->isClientAdmin() && $order->status_id != 1)) {
            return back()->with('message', 'Order is confirmed, you can not edit it');
        }

        $limits= $order->client->limits->where('active', 1);
        
        $client = $order->client;
        $specification = $client->real_specification;

        if($specification != null) {
            $products = Specification::products($specification);

        }else {
            return back()->with('message', 'Cant edit order, client has no specification');
        }

        $rows = DB::table('order_product')
                    ->where('order_id', '=', $order->id)
                    ->get();

        $order_product_ids = $rows
                        ->pluck('product_id')
                        ->all();
        $product_ids = $products
                        ->pluck('product_id')
                        ->all();
        $diff = array_diff($order_product_ids, $product_ids);

        if(!empty($diff)) {
            return back()->with('message', 'Cant edit order, specification has been changed');
        }

        if($products->isNotEmpty()) {
            foreach ($products as $product) {
                $product->amount = $rows
                                    ->where('product_id', $product->product_id)
                                    ->pluck('amount')
                                    ->first();
                $product->total = $rows
                                    ->where('product_id', $product->product_id)
                                    ->pluck('total')
                                    ->first();

            }
            
        }else {
            return back()->with('message', 'Cant edit order, fill specification');
        }

        $this->temporary_rollback_limits($limits, $products, $order);

        foreach ($products as $product) {
            $product->current_value = $limits
                                        ->where('limitable_id', $product->product_id)
                                        ->where('limitable_type', "App\Product")
                                        ->pluck('current_value')
                                        ->first();
        }

        return view('orders.edit', compact('client', 'limits', 'products', 'order'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)//limits, customer and sum update
    {
        $this->authorize('update', $order);

        if((Auth::user()->isConsumer() && 
                ($order->status_id != 1 || strripos($order->sublevel_confirm, 'true') !== false)) || 
            (Auth::user()->isSublevel() && $order->status_id != 1) ||
            (Auth::user()->isClientAdmin() && $order->status_id != 1)) {
            return back()->with('message', 'Order is confirmed, you can not update it');
        }

        $client = $order->client;

        if(array_sum($request['amounts']) === 0) {
            return back()->withInput()->with('message', 'Cant empty order');
        }

        $amounts = array_filter($request['amounts'], "is_numeric");//amount values are not null

        $specification = $client->real_specification;

        $begin = (int)$specification->order_begin;
        $end = (int)$specification->order_end;
        
        if(!$this->check_order_window($begin, $end)) {
            return back()->with('message', 'Sorry, not today');
        }

        if($specification != null) {
            $products = Specification::products($specification);

        }else {
            return back()->withInput()->with('message', 'Cant update order, create and fill specification');
        }

        if($products->isEmpty()) {
            return back()->withInput()->with('message', 'Cant update order, fill specification');
        }
        
        $limits= $client->limits->where('active', 1);

        $rows = DB::table('order_product')
                    ->where('order_id', '=', $order->id)
                    ->get();

        $old_products_ids = $rows->pluck('product_id')->all();

        $old_products = Product::whereIn('product_id', $old_products_ids)->get();

        if($old_products->isNotEmpty()) {
            foreach ($old_products as $old_product) {
                $old_product->amount = $rows
                                    ->where('product_id', $old_product->product_id)
                                    ->pluck('amount')
                                    ->first();
                $old_product->total = $rows
                                    ->where('product_id', $old_product->product_id)
                                    ->pluck('total')
                                    ->first();

            }
            
        }else {
            return back()->with('message', 'Cant update order, fill specification');
        }

        $this->temporary_rollback_limits($limits, $old_products, $order);

        $new_products = $products->whereIn('product_id', array_keys($amounts));

        foreach ($new_products as $new_product) {
            $new_product->amount = $amounts[$new_product->product_id];
            $new_product->total = $new_product->amount * $new_product->price;
        }

        $new_order_sum = array_sum($new_products->pluck('total')->all());

        $exceed_messages = $this->check_limits_exceedence($limits, $new_order_sum, $new_products);


        if(!empty($exceed_messages)) {

            $exceed_messages = array_unique($exceed_messages);
            $message = 'Cant update order. Following limits exceeded: ' . implode(', ', $exceed_messages);
            return back()->withInput()->with('message', $message);
        }
    
        $order->sum = $new_order_sum;
        $order->customer_id = Auth::user()->id;
        $order->created_at = \Carbon\Carbon::now();//neded when checking limits

        DB::transaction(function() use($products, $amounts, $limits, $order){

            $order->save();

            DB::table('order_product')
                ->where('order_id', $order->id)
                ->delete();

            $data = [];

            foreach ($amounts as $product => $amount) {
            $data[] = array('order_id' => $order->id, 
                            'product_id' => $product, 
                            'amount' => $amount, 
                            'price' => $products
                                        ->where('product_id', '=', $product)
                                        ->pluck('price')
                                        ->first()
                            );//total will be created by mysql trigger
            }

            DB::table('order_product')
                ->insert($data);

            if($limits->isNotEmpty()) {
                foreach ($limits as $limit) {
                    $limit->save();
                }
            }   
                
        });

        session()->flash('message', 'Order has been updated');
        
        return redirect()->route('orders', [$client]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    public function mass_set_status_date(SetStatusesRequest $request)
    {
        $statuses = $request['statuses'];
        
        $expected_delivery_dates = $request['dates'];

        $delivery_dates = [];

        $orders = Order::whereIn('id', array_keys($statuses))
                            ->get()
                            ->keyBy('id');

        foreach ($orders as $order) {
            $this->authorize('mass_set_status_date', $order);
        }

        foreach ($statuses as $key => $value) {
            if(isset($expected_delivery_dates[$key])) {
                $time = $expected_delivery_dates[$key];
                unset($expected_delivery_dates[$key]);
                $expected_delivery_dates[$key] = \Carbon\Carbon::parse($time);
            }else {
                $expected_delivery_dates[$key] = null;
            }
        }

        foreach ($statuses as $key => $value) {
            if($value == 4) {
                $delivery_dates[$key] = \Carbon\Carbon::now();
            }else {
                $delivery_dates[$key] = null;
            }
        }

        $manager_comfirmed_orders = collect();
        $manager_canceled_orders = collect();//use when cancel order (for future purposses)

        foreach ($orders as $order) {
            $order->expected_delivery_date = $expected_delivery_dates[$order->id];

            if($order->delivery_date == null) {//set only once
                $order->delivery_date = $delivery_dates[$order->id];
            }

            if(Auth::user()->isSublevel() && $order->status_id == 1) {
                $sublevel_id = Auth::user()->subject->id;
                $sublevel_id_string= '"'.$sublevel_id.'"';
                $status = $statuses[$order->id];
                
                if(!$order->sublevel_confirm) {
                    $order->sublevel_confirm = json_encode([$sublevel_id => $status]);
                    $order->save();
                }else {
                    DB::table('orders')
                    ->where('id', $order->id)
                    ->update(["sublevel_confirm->$sublevel_id_string" => $status]);
                }

            }elseif(Auth::user()->isManager()) {
                if($order->status_id == 2 && $statuses[$order->id] == 3) {
                    $manager_comfirmed_orders = $manager_comfirmed_orders->push($order);
                }elseif($order->status_id == 3 && $statuses[$order->id] == 2) {
                    $order->expected_delivery_date = null;
                    $manager_canceled_orders = $manager_canceled_orders->push($order);
                }
            }
        }

        if(count($manager_comfirmed_orders)) {
            $data = [];
            foreach ($manager_comfirmed_orders as $manager_comfirmed_order) {
                $products = DB::table('order_product')
                            ->where('order_id', $manager_comfirmed_order->id)
                            ->get();

                $products_array = [];
                foreach ($products as $product) {
                    $products_array[] = ["Номенклатура" => Product::where('product_id', $product->product_id)->pluck('model')->first(),
                                    "Количество" => $product->amount,
                                    "Цена" => $product->price,
                                ];
                }

                $item = ["Order_id" => $manager_comfirmed_order->id,
                        "Order_guid" => $manager_comfirmed_order->guid,
                        "Контрагент" => [
                            "client_guid" => $manager_comfirmed_order->
                                                                client->root->guid,
                            "ОКПО" => $manager_comfirmed_order->
                                                        client->root->one_c_id,
                            "Заказчик" => $manager_comfirmed_order->
                                                                client->name,
                            "АдресДоставки" => $manager_comfirmed_order->
                                                                client->address,
                                            ],
                        "Товары" => $products_array,
                        "ДатаСоздания" => ($manager_comfirmed_order->created_at)->toDateTimeString(),
                        "СуммаДокумента" => $manager_comfirmed_order->sum,
                        ];
                $data[] = $item;
            }

            $data = ['Data' => json_encode($data)];

            $Data = $data;

            $soap_server = config('app.wdsl');
            $soap_login = config('app.wdsl_login');
            $soap_pass = config('app.wdsl_pass');

            $target = new \SoapClient($soap_server, 
                    array( 
                    'login' => $soap_login,
                    'password' => $soap_pass,
                    'cache_wsdl' => WSDL_CACHE_NONE,
                    'trace' => 1, 
                    ) 
            );

            $response = $target->DropData($Data);
            $response_items = json_decode($response->return);

            foreach ($response_items as $key=>$response_item) {//keyBy order_id
                unset($response_items[$key]);
                $response_items[$response_item->order_id] = $response_item;
            }

            $error_messages = [];

            foreach ($orders as $order) {
                if(isset($response_items[$order->id])) {
                    if($response_items[$order->id]->result == "True") {
                        $order->guid = $response_items[$order->id]->guid;

                    }else {
                        $error_messages[] = 'order #'.$order->id.' - '. $response_items[$order->id]->error;
                        unset($orders[$order->id]);//if not synced with 1c do not update order status
                    }
                }
            }
        }

        foreach ($orders as $order) {
            if(is_numeric($statuses[$order->id])) {
                $order->status_id = (int)$statuses[$order->id];
            }
            $order->save();
        }
        
        if(!empty($error_messages)) {
            $messages = ', '.implode(', ', $error_messages);
        }else {
            $messages = '';
        }

        $message = 'Satuses updated'.$messages;

        session()->flash('message', $message);
        
        return back();
    }

//slaves

    private function show_sublevel_confirmation(Order $order)
    {
       $confirm_object = (object)(json_decode($order->sublevel_confirm));
        if(Auth::user()->isSublevel()) {
            $confirm_property = Auth::user()->subject->id;

            if(isset($confirm_property) && property_exists($confirm_object, $confirm_property)) {
                $order->sublevel_confirm = $confirm_object->$confirm_property;

            }else {
                $order->sublevel_confirm = 'false';
            }
        }

        $confirm = collect($confirm_object);
        $confirmed = $confirm->filter(function ($value, $key) {//remove all false values
            return $value == "true";
        });
        $confirmed = array_keys($confirmed->toArray());
        $subs_confirmed = Client::whereIn('id', $confirmed)->pluck('name')->all();
        $order->subs = implode(', ', $subs_confirmed);

        return $order;         
    }

    private function make_filter_query(array $filters, array $clients)
    {
        $order = new Order();
        $order = $order->newQuery();

        if (isset($filters['statuses'])) {
            $order->whereIn('status_id', array_keys($filters['statuses']));
        }

        if (isset($filters['clients'])) {
            $order->whereIn('client_id', $filters['clients']);
        }else {
            $order->whereIn('client_id', array_keys($clients));
        }

        if (isset($filters['created_from']) || isset($filters['created_to'])) {
            $created_from = isset($filters['created_from']) ?
                            Carbon::parse($filters['created_from'])->startOfDay() :
                            Carbon::now()->subYears(1000)->startOfDay();

            $created_to = isset($filters['created_to']) ?
                            Carbon::parse($filters['created_to'])->endOfDay() :
                            Carbon::now()->addYears(1000)->endOfDay();

            if($created_from <= $created_to) {
                $order->whereBetween('created_at', [$created_from, $created_to]);
            }
        }

        if (isset($filters['expected_delivery_from']) || isset($filters['expected_delivery_to'])) {
            $expected_delivery_from = isset($filters['expected_delivery_from']) ?
                                    Carbon::parse($filters['expected_delivery_from'])->startOfDay() :
                                    Carbon::now()->subYears(1000)->startOfDay();

            $expected_delivery_to = isset($filters['expected_delivery_to']) ?
                                        Carbon::parse($filters['expected_delivery_to'])->endOfDay() :
                                        Carbon::now()->addYears(1000)->endOfDay();

            if($expected_delivery_from <= $expected_delivery_to) {
                $order->whereBetween('expected_delivery_date', [$expected_delivery_from, $expected_delivery_to]);
            }
        }

        if (isset($filters['delivery_from']) || isset($filters['delivery_to'])) {
            $delivery_from = isset($filters['delivery_from']) ?
                            Carbon::parse($filters['delivery_from'])->startOfDay() :
                            Carbon::now()->subYears(1000)->startOfDay();

            $delivery_to = isset($filters['delivery_to']) ?
                            Carbon::parse($filters['delivery_to'])->endOfDay() :
                                Carbon::now()->addYears(1000)->endOfDay();

            if($delivery_from <= $delivery_to) {
                $order->whereBetween('delivery_date', [$delivery_from, $delivery_to]);
            }
        }

        return $order;        
    }

    private function check_order_window(int $begin, int $end)
    {
        $today = date('d');

        if($begin != 0 && $end != 0) {
            switch ($begin <=> $end) {//check if in order window (day numbers)
                case 0:
                    if ($today != $begin) {// or $today != $end
                        return false;
                    }
                    break;
                
                case -1:
                    if ($begin > $today || $today > $end) {
                        return false;
                    }
                    break;

                case 1:
                    if ($begin > $today && $today > $end) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }

    private function check_limits_exceedence($limits, $order_sum, $products)
    {
        $exceed_messages = [];

        if($limits->isNotEmpty()) {
            foreach ($limits as $limit) {
                if($limit->cron_last_update == null) {
                    $limit->cron_last_update = date('n');
                }
                if($limit->limitable_type == 'Money') {
                    $limit->current_value = $limit->current_value - $order_sum;
                    if($limit->current_value < 0) {
                        $exceed_messages[] = 'money limit';
                    }
                }elseif ($limit->limitable_type == 'App\CostItem') {
                    $cost_item_sum = array_sum($products->where('cost_item', $limit->limitable_id)->pluck('total')->all());
                    $limit->current_value = $limit->current_value - $cost_item_sum;
                    if($limit->current_value < 0) {
                        $exceed_messages[] = 'some of cost_item limits';
                    }
                }elseif ($limit->limitable_type == 'App\Product') {
                    $product_overall = $products->where('product_id', $limit->limitable_id)->pluck('amount')->first();
                    $limit->current_value = $limit->current_value - $product_overall;
                    if($limit->current_value < 0) {
                        $exceed_messages[] = 'some of product limits';
                    }
                }
            }
        }
        return $exceed_messages;
    }

    private function temporary_rollback_limits($limits, $products, $order)
    {
        if($limits->isNotEmpty()) {
            foreach ($limits as $limit) {
                if($order->created_at->timestamp - $limit->created_at->timestamp > 0) {//if order created later than limit
                    if($limit->limitable_type == 'Money') {
                        $limit->current_value = $limit->current_value + $order->sum;
                        
                    }elseif ($limit->limitable_type == 'App\CostItem') {
                        $cost_item_sum = array_sum($products->where('cost_item', $limit->limitable_id)->pluck('total')->all());
                        $limit->current_value = $limit->current_value + $cost_item_sum;
                        
                    }elseif ($limit->limitable_type == 'App\Product') {
                        $product_overall = $products->where('product_id', $limit->limitable_id)->pluck('amount')->first();
                        $limit->current_value = $limit->current_value + $product_overall;
                        
                    }
                }
            }
        }

        return;
    }
}
