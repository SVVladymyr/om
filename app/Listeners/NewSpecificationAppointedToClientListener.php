<?php

namespace App\Listeners;


use App\Events\NewSpecificationAppointedToClient;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\DB;
use App\Specification;

class NewSpecificationAppointedToClientListener
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  NewSpecificationAppointedToClient  $event
     * @return void
     */
    public function handle(NewSpecificationAppointedToClient $event)//change all product limits for client
    {

        $new_specification_id = $event->new_specification_id;
        $old_specification_id = $event->old_specification_id;
        $client_id = $event->client->id;

        if($new_specification_id != null) {
            $new_specification = Specification::where('id', $new_specification_id)->first();
            $new_products = Specification::products($new_specification);
            
            $new_products = $new_products
                            ->where('limit', '<>', null)
                            ->pluck('limit', 'product_id')
                            ->all();

        }else {
            $new_products = null;
        }

        $data_insert = [];
            
        if($new_products != null) {
            foreach ($new_products as $id => $limit) {
                $data_insert[] = array('limitable_id' => $id, 
                                    'limitable_type' => 'App\Product', 
                                    'client_id' => $client_id,
                                    'current_value' => $limit,
                                    'limit' => null,
                                    'active' => 1,
                                    'period' => null,
                                    'cron_last_update' => null,
                                    'created_at' => \Carbon\Carbon::now(),
                                );
            }
        }

        if($old_specification_id != null) {
            $old_specification = Specification::where('id', $old_specification_id)->first();

            /*if($old_specification->main_specification == null) {//when main spec del all subs(editing by manager, moved to controller, bug)
                $old_specification->delete();
            }*/
        }

        DB::transaction(function() use($client_id, $new_products, $data_insert) {

            DB::table('limits')
                ->where('client_id', $client_id)
                ->where('limitable_type', 'App\Product')
                ->delete();

            if($new_products != null) {
                DB::table('limits')
                    ->insert($data_insert);
            }
        });

        
    }
}
