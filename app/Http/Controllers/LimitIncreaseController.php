<?php

namespace App\Http\Controllers;

use App\LimitIncrease;
use Illuminate\Http\Request;

use App\Limit;
use App\Client;
use App\Product;
use App\Description;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;
use App\Mail\LimitIncreaseRequested;


class LimitIncreaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Client $client)
    {
        $this->authorize('limit_increases', $client);

        $items = $client->limit_increases;
        $product_ids = $items->pluck('item')->all();
        $product_ids = array_filter($product_ids, 'is_numeric');
        $product_names = Description::whereIn('product_id', $product_ids)->where('language_id', 1)->pluck('name', 'product_id')->all();
        foreach ($items as $item) {
            if(isset($product_names[$item->item])) {
                $item->item = $product_names[$item->item];
            }
        }

        return view('limit_increases.index', compact('items', 'client'));
    }

    public function set_statuses(Request $request, Client $client)
    {
        $this->authorize('limit_increases', $client);

        $statuses = $request['statuses'];

        $items = $client->limit_increases->whereIn('id', array_keys($statuses));
        foreach ($items as $item) {
            if($statuses[$item->id] === '0') {
                $item->aborted_at = \Carbon\Carbon::now();
                
            }elseif($statuses[$item->id] === '1') {
                $item->confirmed_at = \Carbon\Carbon::now();
            }
            $item->save();
        }

        return back()->with('message', 'items statuses updated');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\LimitIncrease  $limitIncrease
     * @return \Illuminate\Http\Response
     */
    public function show(LimitIncrease $limitIncrease)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\LimitIncrease  $limitIncrease
     * @return \Illuminate\Http\Response
     */
    public function edit(LimitIncrease $limitIncrease)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\LimitIncrease  $limitIncrease
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LimitIncrease $limitIncrease)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\LimitIncrease  $limitIncrease
     * @return \Illuminate\Http\Response
     */
    public function destroy(LimitIncrease $limitIncrease)
    {
        //
    }

    public function limit_increase_request(Request $request, Client $client)
    {
        $this->authorize('limit_increase_request', $client);

        $amounts = request('amounts');

        if(array_sum($amounts) > 0) {
            $insert_data = [];
            foreach ($amounts as $key => $value) {
                $insert_data[] = array('client_id' => $client->id,
                                        'consumer_id' => Auth::user()->id,
                                        'item' => $key,
                                        'amount_asked' => $value,
                                        'created_at' => \Carbon\Carbon::now()
                );
            }
        }

        $product_ids = array_filter(array_keys($amounts), 'is_numeric');

        $products = Description::whereIn('product_id', $product_ids)->where('language_id', 1)->pluck('name', 'product_id')->all();

        foreach ($amounts as $key => $value) {
            if(is_numeric($key)) {
                unset($amounts[$key]);
                $amounts[$products[$key]] = $value;
            }
        }
        $client->amounts = $amounts;
        $client->asking_user = Auth::user()->email;

        if(!empty($insert_data)) {
            DB::table('limit_increases')
                ->insert($insert_data);

            Mail::to($client->root->master->email)->send(new LimitIncreaseRequested($client));

            return back()->with('message', 'Запрос отправлен');

        }else {
            return back()->with('message', 'Пустой запрос, пожалуйста заполните его');
        }
    }
}
