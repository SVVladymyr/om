<?php

namespace App\Http\Controllers;

use App\Limit;
use App\Specification;
use App\Client;
use App\CostItem;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class LimitController extends Controller
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
    public function index(Client $client)
    {
        $this->authorize('limits', $client);

        $limits =  $client->limits;

        $product_limits = $limits->where('limitable_type', '=', 'App\Product');

        if(count($product_limits) != null) {
            $specification = $client->real_specification;

        if($specification) {
            $products = Specification::products($specification);
        }else {
            dd('oops');
        }

            foreach ($product_limits as $product_limit) {
                $product_limit->limit = ($products
                                        ->where('product_id', '=', $product_limit->limitable_id)
                                        ->pluck('limit'))[0];
                $product_limit->period = ($products
                                        ->where('product_id', '=', $product_limit->limitable_id)
                                        ->pluck('period'))[0];

                $product_limit->product_name = $products
                                                ->where('product_id', '=', $product_limit->limitable_id)
                                                ->first()
                                                ->description->name;
            }
        }

        $limits = $limits->merge($product_limits);

        return view('limits.index', compact('limits', 'client'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_limits(Client $client)
    {
        $this->authorize('limits', $client);

        $specification = $client->real_specification;

        if($specification) {
            $products = Specification::products($specification);

        }else {
            $products = collect();
            session()->flash('message', 'Что бы назначить лимиты для продуктов вы должны создать или назначить спецификацию');
        }

        $cost_items = $client->root->cost_items;
        $limits = $client->limits;

        if($limits->isNotEmpty()) {
            if($cost_items->isNotEmpty()) {
                foreach ($cost_items as $cost_item) {
                    if(!$cost_item->class_name)
                    $cost_item->class_name = CostItem::class;

                    $cost_item->active = $limits->where('limitable_type', CostItem::class)
                                                    ->where('limitable_id', $cost_item->id)
                                                    ->pluck('active')->first();
                    $cost_item->limit = $limits->where('limitable_type', CostItem::class)
                                                    ->where('limitable_id', $cost_item->id)
                                                    ->pluck('limit')->first();
                    $cost_item->current_value = $limits->where('limitable_type', CostItem::class)
                                                    ->where('limitable_id', $cost_item->id)
                                                    ->pluck('current_value')->first();
                    $cost_item->limit_id = $limits->where('limitable_type', CostItem::class)
                                                    ->where('limitable_id', $cost_item->id)
                                                    ->pluck('id')->first();
                    $cost_item->period = $limits->where('limitable_type', CostItem::class)
                                                    ->where('limitable_id', $cost_item->id)
                                                    ->pluck('period')->first();
                }
            }else {
                session()->flash('message', 'Что бы назначить лимиты для cost_items вы должны создать cost_items');
            }

            foreach ($products as $product) {
                if(!$product->class_name)
                    $product->class_name = Product::class;
                
                $product->active = $limits->where('limitable_type', Product::class)
                                                    ->where('limitable_id', $product->product_id)
                                                    ->pluck('active')->first();
                $product->current_value = $limits->where('limitable_type', Product::class)
                                                    ->where('limitable_id', $product->product_id)
                                                    ->pluck('current_value')->first();
                $product->limit_id = $limits->where('limitable_type', Product::class)
                                                    ->where('limitable_id', $product->product_id)
                                                    ->pluck('id')->first();
            }

            $money_limit = $limits->where('limitable_type', 'Money')->first();

            if($money_limit == null) {
                $money_limit = new Limit();
            }

        }else {
            $money_limit = new Limit();
            if($cost_items->isEmpty()) {
                session()->flash('message', 'Что бы назначить лимиты для cost_items вы должны создать cost_items');
            }
        }

        $products = $products->where('limit', '<>', null);

        return view('limits.set', compact('cost_items', 'client', 'products', 'money_limit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function fill_limits(Request $request, Client $client)//touch limit_increase table in future todo
    {
        $this->authorize('limits', $client);
//        dd($request->all());
        $types = $request['type'];
        $limits = $request['limit'];
        $periods = $request['period'];
        $values = $request['value'];
        $actives = $request['active'];
        $ids = $request['id'];
        $limit_ids = $request['limit_id'];

        $specification = $client->real_specification;

        if($specification != null) {
            $products = Specification::products($specification);

        }else {
            return back()->with('message', 'Невозможно назначить лимиты, у клиента нет спецификации');
        }

        $product_limits_exist = $products->pluck('limit', 'product_id')->all();
        $product_periods_exist = $products->pluck('period', 'product_id')->all();

        foreach ($ids as $key => $value) {
            if($products != null && $types[$key] == 'App\Product') {
                $periods[$key] = $product_periods_exist[$value];
                $limits[$key] = $product_limits_exist[$value];
            }

            if($limits[$key] == 0
                || ($limits[$key] == null && $types[$key] != 'App\Product')
                || ($values[$key] == null && $types[$key] == 'App\Product')) {
                unset($ids[$key]);

            }elseif($limits[$key] < $values[$key] || $values[$key] == null) {
                $values[$key] = $limits[$key];
            }

            if(($periods[$key] == 0 || $periods[$key] == null) &&
                ($limits[$key] != 0 || $limits[$key] != null)) {
                return back()->withInput()->with('message', 'Заполните периоды');
            }

            /*if($types[$key] == 'App\Product') {
                $periods[$key] = null;
                $limits[$key] = null;
            }*/
        }

        $rows_exist = DB::table('limits')
                    ->select('cron_last_update', 'id', 'created_at', 'active')
                    ->where('client_id', $client->id)
                    ->get();

        $data_update = [];

        $data_insert = [];

            foreach ($ids as $key => $value) {
                 if($limit_ids[$key] != null) {
                    $cron_last_update = $rows_exist->where('id', $limit_ids[$key])->pluck('cron_last_update')->first();

                    $created_at = $rows_exist->where('id', $limit_ids[$key])->pluck('created_at')->first();

                    $active = $rows_exist->where('id', $limit_ids[$key])->pluck('active')->first();

                    if((int)$actives[$key] > $active) {//if limit was activated make it like new created, needed when updating order
                        $created_at = \Carbon\Carbon::now();
                    }

                    $data_update[] = array('id' => $limit_ids[$key],
                                'limitable_id' => (int)$value,
                                'limitable_type' => $types[$key],
                                'client_id' => $client->id,
                                'current_value' => (int)$values[$key],
                                'limit' => $limits[$key],
                                'active' => (int)$actives[$key],
                                'period' => $periods[$key],
                                'cron_last_update' => $cron_last_update,
                                'created_at' => $created_at,
                                'updated_at' => \Carbon\Carbon::now(),
                            );

                } else {
                    $data_insert[] = array('limitable_id' => (int)$value,
                                'limitable_type' => $types[$key],
                                'client_id' => $client->id,
                                'current_value' => (int)$values[$key],
                                'limit' => $limits[$key],
                                'active' => (int)$actives[$key],
                                'period' => $periods[$key],
                                'cron_last_update' => null,
                                'created_at' => \Carbon\Carbon::now(),
                            );
                }


            }

        DB::transaction(function() use($client, $data_insert, $data_update){
            if($client->limits != null) {
                $client->limits()->delete();
            }

            if($data_insert != null) {
                DB::table('limits')
                ->insert($data_insert);
            }

            if($data_update != null) {
                DB::table('limits')
                ->insert($data_update);
            }

        });

        return redirect("clients/$client->id/limits");
    }

  public static function reset_product_limits(Client $client, $specification)//for refactoring
  {
    //
  }



}
