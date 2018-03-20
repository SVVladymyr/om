<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Specification;
use App\Product;
use App\Description;
use App\Client;
use App\Limit;
use App\User;
use App\Order;
use App\Role;
use App\Status;
use Excel;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }

    public function reports(Request $request)//from < to request validation todo
    {
        $this->validate($request, [
        'from' => 'date|required',
        'to' => 'date|after_or_equal:from|before:tomorrow|required',
        ]);

        $to = !is_null($request['to']) ?
                Carbon::parse($request['to'])->endOfDay() :
                Carbon::today()->endOfDay();
        $from = !is_null($request['from']) ?
                Carbon::parse($request['from'])->startOfDay() :
                $to->copy()->subMonths(6)->startOfDay();
//dd($to, $from);
        $user = Auth::user();

        $roles_rows = Role::get()->toArray();
        $statuses_rows = Status::get()->toArray();

        if($client = $user->employer) {
            $cost_items_rows = $client->cost_items->toArray();

            if(!empty($clients_rows =  $client->network->toArray())) {
                $clients_ids = array_column($clients_rows, 'id');
                $limits_rows = Limit::whereIn('client_id', $clients_ids)->get()->toArray();

                if($specifications_ids = array_filter(array_unique(array_column($clients_rows, 'specification_id')), 'is_numeric')) {
                    $specifications_rows = Specification::whereIn('id', $specifications_ids)->get()->toArray();
                    $specification_product_rows = DB::table('product_specification')
                                                    ->whereIn('specification_id', $specifications_ids)
                                                    ->get()->toArray();

                    $specification_products_ids = array_column($specification_product_rows, 'product_id');

                    foreach ($specification_product_rows as $item => $value) {
                    unset($specification_product_rows[$item]);
                    $specification_product_rows[$item] = json_decode(json_encode($value), true);
                    }
                }else {
                    $specifications_rows = [];
                    $specification_product_rows = [];
                    $specification_products_ids = [];
                }
            }       
            $users_rows = $client->hired->push($client->manager)->toArray();

            if(!empty($orders_rows = $client->orders
                                    ->where('created_at', '>=', $from)
                                    ->where('created_at', '<=', $to)
                                    ->toArray())) {
                $orders_ids = array_column($orders_rows, 'id');
                $order_product_rows = DB::table('order_product')
                                        ->whereIn('order_id', $orders_ids)
                                        ->get()->toArray();
                foreach ($order_product_rows as $row => $val) {
                    unset($order_product_rows[$row]);
                    $order_product_rows[$row] = json_decode(json_encode($val), true);
                }

                $order_products_ids = array_column($order_product_rows, 'product_id');
            }else {
                $orders_rows = [];
                $order_product_rows = [];
                $order_products_ids = [];
            }
        }else {
            $clients_rows = [];
            $users_rows = [];
        }

        $products_ids = array_unique(array_merge($order_products_ids, $specification_products_ids));

        $products = Product::whereIn('product_id', $products_ids)->pluck('model', 'product_id')->all();
        $descriptions = Description::whereIn('product_id', $products_ids)->where('language_id', 1)->pluck('name', 'product_id')->all();
        $products_rows = [];
        foreach ($products as $key => $value) {
            $products_rows[] = ['product_id' => $key, 'model' => $value, 'name' => $descriptions[$key]];
        }

        Excel::create('OM24: CRM system. Report file', function($excel) use($user, $client, $clients_rows, $users_rows, $orders_rows, $order_product_rows, $cost_items_rows, $specifications_rows, $specification_product_rows, $limits_rows, $roles_rows, $statuses_rows, $products_rows){

            $excel->setTitle('BD data')
                    ->setCreator("$user->email")
                    ->setCompany("$client->name")
                    ->setDescription('my company and reports');

            $excel->sheet('products', function($sheet) use($products_rows){
                $sheet->with($products_rows);
            });

            $excel->sheet('roles', function($sheet) use($roles_rows){
                $sheet->with($roles_rows);
            });

            $excel->sheet('statuses', function($sheet) use($statuses_rows){
                $sheet->with($statuses_rows);
            });

            $excel->sheet('users', function($sheet) use($users_rows){
                $sheet->with($users_rows);
            });

            $excel->sheet('clients', function($sheet) use($clients_rows){
                $sheet->with($clients_rows);
            });

            $excel->sheet('limits', function($sheet) use($limits_rows){
                $sheet->with($limits_rows);
            });

            $excel->sheet('orders', function($sheet) use($orders_rows){
                $sheet->with($orders_rows);
            });

            $excel->sheet('order_product', function($sheet) use($order_product_rows){
                $sheet->with($order_product_rows);
            });

            $excel->sheet('cost_items', function($sheet) use($cost_items_rows){
                $sheet->with($cost_items_rows);
            });

            $excel->sheet('specifications', function($sheet) use($specifications_rows){
                $sheet->with($specifications_rows);
            });

            $excel->sheet('specification_product', function($sheet) use($specification_product_rows){
                $sheet->with($specification_product_rows);
            });

        })->export('xlsx');

        return back();
    }
}
