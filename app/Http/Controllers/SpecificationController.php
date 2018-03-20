<?php

namespace App\Http\Controllers;

use App\Specification;
use App\Product;
use App\Description;
use App\Client;
use App\User;
use App\Order;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Http\Requests\SpecificationRequest;

use Illuminate\Support\Facades\Mail;
use App\Mail\MainSpecificationChanged;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Excel;

class SpecificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', Specification::class);
        
        if(Auth::user()->isClientAdmin()) {
            if(Auth::user()->employer->specification != null) {
                $specifications = Auth::user()->employer->specification->sub_specifications;
                $specification = Auth::user()->employer->specification->where('id', '=', Auth::user()->employer->specification_id)->get();
                $specifications = $specification->merge($specifications);
            }else {
                $specifications = collect();
                session()->flash('message', 'No specifications');
            }

        }elseif (Auth::user()->isManager()) {
            $specifications = Auth::user()->specifications;

        }else {
            $specifications = collect();
            session()->flash('message', 'No specifications');
        }       
        
        return view('specifications.index', compact('specifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->isClientAdmin()){
            $specification = Auth::user()->employer->specification;

            if($specification) {
                $products = Specification::products($specification);
            }else {
                $products = collect();
                session()->flash('message', 'To fill specification with products your employer must have one');
            }
        }elseif(Auth::user()->isManager()) {
            $products = collect();
        }


        return view('specifications.create', compact('products'));
    }

    /**
     * Show the form for importing a file.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request, Specification $specification)//clean appropriate order_product lines if order status not manager_confirm 
    {
        $this->authorize('upload', $specification);

        $validator = Validator::make(
          ['type' => $request->file('file')->getClientOriginalExtension(),
            ],
          ['type' => 'in:xlsx, xls',
        ])->validate();

        $file = $request->file('file')->getRealPath();
        
        $data = Excel::selectSheetsByIndex(0)->load($file, function($reader) {
            $reader->select(array('model', 'price'));
            })->toArray();

        foreach ($data as $key => $value) {
            unset($data[$key]);
            $data[$value['model']] = $value['price'];
        }

        $pids = Product::whereIn('model', array_keys($data))->pluck('product_id', 'model');

        $dat = [];
        foreach ($pids as $mod => $pid) {
            $dat[] = ['product_id' => $pid, 'price' => $data[(int)$mod], 'specification_id' => $specification->id];
        }      
        
        $product_price = collect($dat);

        $product_price = $product_price->mapWithKeys(function ($item) {
            return [$item['product_id'] => $item['price']];
        });

        $old_product_price = DB::table('product_specification')
                        ->where('specification_id', '=', $specification->id)
                        ->get();
        $old_product_price_ids= $old_product_price->pluck('price', 'product_id');


        $delete_product_ids = $old_product_price_ids->diffKeys($product_price)
                                                    ->keys()->toArray();
        $insert_product_ids = $product_price->diffKeys($old_product_price_ids)
                                                    ->keys()->toArray();
        $insert_data = $product_price->only($insert_product_ids)->all();

        foreach ($insert_data as $id => $price) {
            unset($insert_data[$id]);
            $insert_data[$id] = array('product_id' => $id,
                                    'specification_id' => $specification->id,
                                    'price' => $price);
            
        }

        $update_data = $product_price->only($old_product_price_ids->keys()->toArray())->all();

        if(!empty($delete_product_ids) && count($specification->clients) > 0) {
        
            $clients_ids = $specification->clients->first()->network()->pluck('id')->all();//bug if not appointed to client

        $orders_ids= Order::whereIn('client_id', $clients_ids)->where('status_id', '<>', 4)->pluck('id')->all();//except delivered

        $affected_orders_ids = DB::table('order_product')
                            ->whereIn('order_id', $orders_ids)
                            ->whereIn('product_id', $delete_product_ids)
                            ->distinct()
                            ->pluck('order_id')
                            ->all();

        $affected_orders = Order::whereIn('id', $affected_orders_ids)->get();

        foreach ($affected_orders as $order) {
                $order->link = action('OrderController@show', ['id' => $order->id]);
            }

        $affected_clients_ids = $affected_orders->pluck('client_id')->unique();

        $affected_clients = Client::whereIn('id', $affected_clients_ids)->get();

        foreach ($affected_clients as $affected_client) {
            $affected_client->orders = $affected_orders->where('client_id', $affected_client->id);

            Mail::to($affected_client->master->email)->send(new MainSpecificationChanged($affected_client));

        }

        $client_admin = $specification->clients->first();

        $client_admin->orders = $affected_orders;

        Mail::to($client_admin->master->email)->send(new MainSpecificationChanged($client_admin));


        $products = Specification::products($specification);

        session()->flash('message', 'This view is temporary and shown only once');

        return view('specifications.show', compact('products', 'specification', 'affected_clients'));

        }else {
            $affected_clients = collect();
        }

        $sub_specifications_ids = $specification->sub_specifications->pluck('id')->all();

        if($sub_specifications_ids == null) {
            $specifications_ids = [$specification->id];
        }else {
            $specifications_ids = array_merge($sub_specifications_ids, [$specification->id]);
        }

        $clients_ids = DB::table('clients')
                            ->whereIn('specification_id', $specifications_ids)
                            ->pluck('id')
                            ->all();


        DB::transaction(function () use ($specifications_ids, $delete_product_ids, $specification, $insert_data, $update_data, $clients_ids){
        
            if($delete_product_ids != null) {
                DB::table('product_specification')
                    ->whereIn('specification_id', $specifications_ids)
                    ->whereIn('product_id', $delete_product_ids)
                    ->delete();

                DB::table('limits')
                    ->whereIn('client_id', $clients_ids)
                    ->whereIn('limitable_id', $delete_product_ids)
                    ->where('limitable_type', 'App\Product')                    
                    ->delete();
            }

            if($insert_data != null) {
                DB::table('product_specification')
                    ->insert($insert_data);
            }

            if($update_data != null) {
                foreach ($update_data as $id => $price) {
                    DB::table('product_specification')
                        ->where('specification_id', $specification->id)
                        ->where('product_id', $id)
                        ->update(['price' => $price]);
                }
            }

        });

        $products = Specification::products($specification);
            
        return view('specifications.show', compact('products', 'specification', 'affected_clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SpecificationRequest $request)
    {
        $this->authorize('create', Specification::class);

        if(Auth::user()->isClientAdmin()) {
            $main_id = Auth::user()->employer->specification->id;
            $manager_id = null;

        }elseif (Auth::user()->isManager()) {
            $main_id = null;
            $manager_id = Auth::user()->id;

        } 

        $specification = Specification::create([
            'name' => request('name'),
            'order_begin' => request('order_begin'),
            'order_end' => request('order_end'),
            'main_id' => $main_id,
            'manager_id' => $manager_id,
        ]);

        if($items = $request['items']) {
            $id = $specification->id;
            $limits = $request['limits'];
            $periods = $request['periods'];
            
            $data = [];

            foreach ($items as $key => $value) {
                if($limits[$value] == 0) {
                    $limits[$value] = null;
                }
                
                $data[] = array('specification_id' => $id, 'product_id' => (int)$value,
                                'limit' => $limits[$value], 'period' => $periods[$value]);
                
            }
 
            DB::table('product_specification')
                ->insert($data);
        }

        return redirect("specifications/$specification->id")->with('message', 'New specification has been created');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function show(Specification $specification)
    {
        $this->authorize('view', $specification);
        if(Auth::user()->isClientAdmin()) {
            $products = Specification::products($specification);
        
        }elseif(Auth::user()->isManager()) {
            $products = Specification::products($specification);
        }
            
        return view('specifications.show', compact('products', 'specification'));        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function edit(Specification $specification)
    {
        $this->authorize('update', $specification);

        if(Auth::user()->isClientAdmin()){
            $sub_products = Specification::products($specification)->keyBy('product_id');

            if($sub_products->isNotEmpty()) {
                foreach ($sub_products as $sub_product) {
                    $sub_product->selected = true;
                }
            }else {
                $sub_products = collect();
            }
            
            if($specification->main_specification != null) {
                $main_products = Specification::products($specification->main_specification)->keyBy('product_id');

                if($main_products->isNotEmpty()) {
                    foreach ($main_products as $main_product) {
                        $main_product->limit = null;
                        $main_product->period = null;
                        $main_product->selected = false;
                    }
                }else {
                    $main_products = collect();
                }
            }else {
                    $main_products = collect();
                }

            $products = $main_products->merge($sub_products);

        }elseif(Auth::user()->isManager()) {
            $products = collect();
        }

        if($specification->main_specification == null) {
            session()->flash('message', 'You are editing main specification');
        }

        return view('specifications.edit', compact('specification', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function update(SpecificationRequest $request, Specification $specification)//check current values when limit update todo
    {
        if(Auth::user()->isClientAdmin()) {

            $items = (array)$request['items'];
            $id = $specification->id;
            $limits = $request['limits'];
            $periods = $request['periods'];

            if($specification->main_specification == null) {
                $root_client = $specification->clients->first();
                $clients_ids = $root_client->network->where('specification', null)->pluck('id')->all();
                $clients_ids[] = $root_client->id;

                $cost_items_prices = DB::table('product_specification')
                                        ->where('specification_id', '=', $id)
                                        ->whereIn('product_id', array_keys($items))
                                        ->get(['cost_item_id', 'product_id', 'price']);
                
                $cost_items = $cost_items_prices->pluck('cost_item_id', 'product_id')->toArray();
                $prices = $cost_items_prices->pluck('price', 'product_id')->toArray();
            }else {
                $clients_ids = $specification->clients->pluck('id')->all();
                $cost_items = array_fill_keys(array_keys($items), null);
                $prices = array_fill_keys(array_keys($items), null);
            }

            $data = [];

            $limits_data = [];

            $limits_items_delete = [];

            foreach ($items as $key => $value) {
                if($limits[$value] != null && $periods[$value] == null) {
                    return back()->withInput()->with('message', 'Set periods');
                }

                if($limits[$value] == 0 || $limits[$value] == null) {
                    $limits[$value] = null;
                    $periods[$value] = null;
                    $limits_items_delete[$value] = $value;
                }else {
                    $limits_data[$value] = array('current_value' => $limits[$value], 
                                            'limitable_id' => (int)$value,
                                            'limitable_type' => 'App\Product',
                                            'period' => null,
                                            'active' => 1,
                                            'created_at' => \Carbon\Carbon::now(),
                                        );
                }
                
                $data[$value] = array('specification_id' => $id, 
                                'product_id' => (int)$value,
                                'limit' => $limits[$value], 
                                'period' => $periods[$value],
                                'cost_item_id' => $cost_items[$value], 
                                'price' => $prices[$value]
                            );
                
            }

            $items_exist = DB::table('product_specification')
                                ->where('specification_id', '=', $id)
                                ->pluck('limit', 'product_id')
                                ->toArray();

            $limits_exist = DB::table('limits')
                                ->select('id', 'current_value', 'limitable_id')
                                ->whereIn('client_id', $clients_ids)
                                ->whereIn('limitable_id', array_keys($items_exist))
                                ->where('limitable_type', 'App\Product')
                                ->get();
            $limits_exist_ids = $limits_exist
                                ->pluck('id', 'limitable_id')
                                ->toArray();

            $limits_exist = $limits_exist
                            ->pluck('current_value', 'limitable_id')
                            ->toArray();

            $limits_items_insert = array_diff_key($limits_data, $limits_exist);

            $limits_items_update = array_diff_key($limits_data, $limits_items_insert);

            foreach ($limits_items_update as $__id => $row) {
                unset($limits_items_update[$__id]);

                if($row['current_value'] < $limits_exist[$__id]) {
                    $row['id'] = $limits_exist_ids[$__id];
                    $limits_items_update[$__id] = $row;
                }
            }

            $limits_items_insert_data = [];

            foreach ($clients_ids as $num => $client) {
                foreach ($limits_items_insert as $key => $value) {
                    $value['client_id'] = $client;
                    $limits_items_insert_data[] = $value;
                }
            }

            $items_delete = array_diff_key($items_exist, $items);

            $limits_items_delete = array_merge($limits_items_delete, $items_delete);

            $clients_orders_ids = DB::table('orders')
                                    ->whereIn('client_id', $clients_ids)
                                    ->whereIn('status_id',[1, 2])//new or client_admin_comfirm
                                    ->distinct()
                                    ->pluck('id')
                                    ->all();

            DB::transaction(function () use ($id, $data, $clients_ids, $clients_orders_ids, $items_delete, $limits_items_delete,$limits_items_insert_data, $limits_items_update){

                DB::table('limits')
                    ->whereIn('client_id', $clients_ids)
                    ->whereIn('limitable_id', $limits_items_delete)
                    ->where('limitable_type', '=', 'App\Product')
                    ->delete();

                DB::table('limits')
                    ->insert($limits_items_insert_data);

                if(!empty($limits_items_update))  {
                    foreach ($limits_items_update as $lim => $row) {
                        DB::table('limits')
                            ->where('id', $row['id'])
                            ->update($row);
                    }
                }

                DB::table('order_product')
                    ->whereIn('order_id', $clients_orders_ids)
                    ->whereIn('product_id', $items_delete)
                    ->delete();

                $orders_not_empty = DB::table('order_product')
                                        ->whereIn('order_id', $clients_orders_ids)
                                        ->distinct()
                                        ->pluck('order_id')
                                        ->all();

                $empty_orders_ids= array_diff($clients_orders_ids, $orders_not_empty);

                if(!empty($empty_orders_ids)) {
                    DB::table('orders')
                        ->whereIn('id', $empty_orders_ids)
                        ->delete();
                }


                DB::table('product_specification')
                    ->where('specification_id', '=', $id)
                    ->delete();
            
                DB::table('product_specification')
                    ->insert($data); 
            });
        
                        
        }

        if($request['name'] != null) {
           $specification->name = request('name');
        }
        if($request['order_begin'] != null) {
           $specification->order_begin = (int)request('order_begin');
        }
        if($request['order_end'] != null) {
           $specification->order_end = (int)request('order_end');
        }

        $specification->save();

        return redirect("specifications/$specification->id")->with('message', 'Specification has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Specification  $specification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Specification $specification)
    {
        $this->authorize('delete', $specification);

        $specification->delete();

        return redirect('specifications')->with('message', 'Specification has been deleted');
    }
}
