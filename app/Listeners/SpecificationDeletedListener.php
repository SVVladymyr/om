<?php

namespace App\Listeners;

use App\Events\SpecificationDeleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use Illuminate\Support\Facades\DB;
use App\Specification;

class SpecificationDeletedListener
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
     * @param  SpecificationDeleted  $event
     * @return void
     */
    public function handle(SpecificationDeleted $event)
    {
        $specification = $event->specification;

        $sub_specifications = $specification->sub_specifications;

        if($sub_specifications != null) {
            $specifications_ids = $sub_specifications->push($specification)->pluck('id')->all();
        }else {
            $specifications_ids = [$specification->id];
        }

        $clients_ids = DB::table('clients')
                            ->whereIn('specification_id', $specifications_ids)
                            ->pluck('id')
                            ->all();

        DB::transaction(function () use ($specification, $specifications_ids, $clients_ids) {

            Specification::whereIn('id', $specifications_ids)
                            ->delete();

            DB::table('product_specification')
                ->whereIn('specification_id',$specifications_ids)
                ->delete();

            DB::table('limits')
                ->whereIn('client_id', $clients_ids)
                ->where('limitable_type', '=', 'App\Product')//check
                ->delete();
        });
    }
}
