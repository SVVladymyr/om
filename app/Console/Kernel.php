<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

use App\Order;
use App\Specification;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $month_number = date('n');

        $periods = DB::table('limits')
                        ->distinct()
                        ->pluck('period')
                        ->toArray();
        
        foreach ($periods as $period) {
            $cron_last_update = ($month_number - $period > 0) ?
                                ($month_number - $period) :
                                ($month_number - $period + 12);

            $schedule->call(function () use($period, $cron_last_update){
                DB::table('limits')
                    ->where([['cron_last_update', $cron_last_update],
                            ['period', $period]])
                    ->update([['current_value' => DB::raw("`limit`")],
                                ['cron_last_update' => $month_number]]);
            })->cron("30 3 1 $month_number * *");
        }
//--------------------------------------------------------------------------------
        $day_number = date('d');

        $specification_ids = DB::table('specifications')
                                        ->where('order_begin', '=', $day_number + 1)
                                        ->pluck('id', 'id')
                                        ->toArray();

        if(!empty($specification_ids)) {
            $client_ids =  DB::table('clients')
                            ->whereIn('specification_id', $specification_ids)
                            ->pluck('ancestor_id', 'id')
                            ->toArray();
            $clients = array_keys($client_ids);

            foreach ($client_ids as $client => $ancestor) {
                if($ancestor == null) {
                    $net = DB::table('clients')
                            ->where('root_id', $client)
                            ->where('specification_id', null)
                            ->pluck('id')
                            ->toArray();

                }

                if(!empty($net)) {
                    $clients = array_merge($clients, $net);
                }
            }
        }
        
        if(!empty($clients)) {
            $orders_delete = Order::whereIn('client_id', $clients)
                                    ->whereIn('status_id', [1, 2])//new and client_admin confirmed
                                    ->get();
        }

        if(isset($orders_delete) && $orders_delete->isNotEmpty()) {
            $schedule->call(function () use($orders_delete){            

                foreach ($orders_delete as $order) {//reset limits 
                    $client = $order->client;
                    $specification = $client->real_specification;

                    if($specification != null) {
                        $products = Specification::products($specification);

                    }else {
                        dd('oops');//log todo
                    }

                    $rows = DB::table('order_product')
                        ->where('order_id', '=', $order->id)
                        ->get();

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
                    }

                    $limits = $client->limits;

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

                            $limit->save();
                        }
                    }
                }

                DB::table('order_product')
                    ->whereIn('order_id', '=', $orders_delete)
                    ->delete();

                DB::table('orders')
                    ->where('order_id', '=', $orders_delete)
                    ->delete();
                
            })->cron("30 3 $day_number * * *");
        }
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
