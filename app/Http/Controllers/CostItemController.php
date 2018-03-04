<?php

namespace App\Http\Controllers;

use App\CostItem;
use App\Specification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CostItemRequest;
use Illuminate\Support\Facades\DB;


use App\Client;

class CostItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Appoint cost_item for the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function set_product_cost_items()
    {
        $this->authorize('index', CostItem::class);

        if(Auth::user()->subject) {
            $cost_items = Auth::user()->employer->cost_items->pluck('name', 'id')->all();
            if(empty($cost_items)) {
                return back()->with('message', 'Cant fill specification with cost_items. No cost_items');
            }

            $specification = Auth::user()->employer->specification;

            if($specification != null) {
                $products = Specification::products($specification);
                
            }else {
                $products = collect();
            }

            if($products->isEmpty()) {
                return back()->with('message', 'Cant fill specification with cost_items. No specification or empty specification');
            }

            $products = $products->sortBy('cost_item');
        }else {
            return back()->with('message', 'Cant fill specification with cost_items. No permission');
        }

        

        return view('cost_items.fill', compact('cost_items', 'products'));
    }

    /**
     * Appoint cost_item for the product.
     *
     * @return \Illuminate\Http\Response
     */
    public function fill_product_cost_items(Request $request)
    {
        $this->authorize('index', CostItem::class);

        if($items = $request['items']) {
            $id = Auth::user()->employer->specification->id;

            foreach ($items as $key => $value) {
                DB::table('product_specification')
                ->where('specification_id', '=', $id)
                ->where('product_id', '=', (int)$key)
                ->update(['cost_item_id' => $value]);
            }            
        }

        return $this->set_product_cost_items();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', CostItem::class);

        if(Auth::user()->subject) {
            $cost_items = Auth::user()->employer->cost_items()->get();
        }else {
            $cost_items = [];
            session()->flash('message', 'No cost_items');
        }

        
        return view('cost_items.index', compact('cost_items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cost_items.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CostItemRequest $request)
    {
        $this->authorize('create', CostItem::class);

        $client_id = Auth::user()->employer->id;


        CostItem::create([
            'name' => request('name'),
            'client_id' => $client_id,
        ]);


        return redirect('cost_items')->with('message', 'New cost_item has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CostItem  $cost_item
     * @return \Illuminate\Http\Response
     */
    public function show(CostItem $cost_item)
    {
        $this->authorize('view', $cost_item);

        return view('cost_items.show', compact('cost_item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CostItem  $cost_item
     * @return \Illuminate\Http\Response
     */
    public function edit(CostItem $cost_item)
    {
        $this->authorize('update', $cost_item);

        return view('cost_items.edit', compact('cost_item'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CostItem  $cost_item
     * @return \Illuminate\Http\Response
     */
    public function update(CostItemRequest $request, CostItem $cost_item)
    {
        $cost_item->name = request('name');

        $cost_item->save();

        return redirect("/cost_items/$cost_item->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CostItem  $cost_item
     * @return \Illuminate\Http\Response
     */
    public function destroy(CostItem $cost_item)
    {
        $this->authorize('delete', $cost_item);

        DB::transaction(function () use ($cost_item){

            $cost_item->delete();

            DB::table('limits')
                ->where([
                        ['limitable_id', '=', $cost_item->id],
                        ['limitable_type', '=', $cost_item->class_name]
                    ])
                ->delete();

            DB::table('product_specification')
                ->where('cost_item_id', '=', $cost_item->id)
                ->update(['cost_item_id' => null]);
        });
             

        return redirect('cost_items')->with('message', 'Cost_item has been deleted');
    }
}
