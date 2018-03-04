<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ClientRequest;
use App\User;

use App\Events\NewSpecificationAppointedToClient;

class ClientController extends Controller
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
    public function index(Client $clients)
    {
        if(Auth::user()->isConsumer()
            || Auth::user()->isSublevel()) {
            $clients = Auth::user()->subject()->get();

        }elseif(Auth::user()->isClientAdmin()) {
            $clients = Auth::user()->subject()->get();

        }elseif(Auth::user()->isManager()) {
            $clients = Auth::user()->clients()->get();

        }elseif(Auth::user()->isCompanyAdmin()) {
            $clients = $clients->doesntHave('ancestor')->get();

        }else {
            session()->flash('message', 'Something went wrong');
            return redirect('back');
        }

        $clients = $clients->sortBy('name');

        if($clients->isEmpty()) {
            session()->flash('message', 'No clients');
        }

        return view('clients.index', compact('clients'));
    }


    public function network(Client $client)
    {
        $this->authorize('network', $client);

        if(Auth::user()->isCompanyAdmin() ||
            Auth::user()->isManager() ||
            Auth::user()->isClientAdmin()) {
            $clients = $client->network()->get();

        }elseif(Auth::user()->isSublevel()) {
            $clients = $client->expand_network()->push($client);

        }else {
            $clients = collect();
            $clients = $clients->push($client);
        }

        $clients = $clients->sortBy('ancestor_id');

        if($clients->isEmpty()) {
            session()->flash('message', 'No clients');
        }

        return view('clients.network', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(User $users, Client $clients)
    {
        if(Auth::user()->isCompanyAdmin()) {
            $roots = $clients->doesntHave('ancestor')->pluck('name', 'id')->all();
            $ancestors = $clients->pluck('name', 'id')->all();
            $masters = $users->doesntHave('subject')->whereIn('role_id',[2, 4, 5])->pluck('email', 'id')->all();
            $managers = $users->where('role_id', 3)->pluck('email', 'id')->all();
            $specifications = collect();

        }elseif(Auth::user()->isClientAdmin()) {
            $roots =  collect();
            $ancestors = Auth::user()->employer->network()->pluck('name', 'id')->all();
            $masters = Auth::user()->employer->hired()->doesntHave('subject')->pluck('email', 'id')->all();
            $specifications = Auth::user()->employer->specification->sub_specifications()->pluck('name', 'id')->all();
            $managers = collect();
        }

        return view('clients.create', compact('ancestors', 'masters', 'managers', 'roots', 'specifications'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request, User $users, Client $clients)
    {
        if(Auth::user()->isClientAdmin()) {
            if(!$request->has('ancestor_id')) {
                return back()->withInput()->with('message', 'You can not create root, please enter ancestor');
            }elseif($request->has('manager_id')) {
                return back()->withInput()->with('message', 'You can not appoint a manager');
            }

            $ancestors = Auth::user()->employer->network->pluck('id')->all();
            $masters = Auth::user()->employer->hired()->doesntHave('subject')->pluck('id')->all();
            $managers = collect();
            $specification_id = $request['specification_id'];

        }elseif(Auth::user()->isCompanyAdmin()){
            $specification_id = null;
            $ancestors = $clients->pluck('id')->all();
            $masters = $users->doesntHave('subject')->whereIn('role_id',[2, 4, 5])->pluck('id')->all();
            $managers = $users->where('role_id', 3)->pluck('id')->all();
        }

        if($request['ancestor_id'] != null && !in_array(request('ancestor_id'), $ancestors)) {
                return back()->withInput()->with('message', 'This user can not be ancestor');

        }elseif($request['master_id'] != null && !in_array(request('master_id'), $masters)) {
                return back()->withInput()->with('message', 'This user can not be master');
        }elseif($request['manager_id'] != null && !in_array(request('manager_id'), $managers)) {
                return back()->withInput()->with('message', 'User must be a manager');
        }

        $client = Client::create([
            'guid' => request('guid'),
            'one_c_id' => request('one_c_id'),
            'name' => request('name'),
            'code' => request('code'),
            'manager_id' => request('manager_id'),
            'master_id' => request('master_id'),
            'ancestor_id' => request('ancestor_id'),
            'specification_id' => $specification_id,
            'phone_number' => request('phone_number'),
            'address' => request('address'),
            'main_contractor' => request('main_contractor'),
            'organization' => request('organization'),
        ]);

        if($client->ancestor === null) {
            $client->root_id = $client->id;

        }else {
            $client->root_id = $client->ancestor->root->id;
            $client->manager_id = null;

        }

        $client->save();

        return redirect('clients')->with('message', 'New client has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
       $this->authorize('view', $client);

        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(User $users, Client $client)
    {
        $this->authorize('update', $client);

        if(Auth::user()->isCompanyAdmin()) {
            $ancestors_all = $client->root->network()->get();
            $ancestors_exceptions = $client->expand_network()->push($client);

            $ancestors = $ancestors_all->diff($ancestors_exceptions)->pluck('name', 'id')->all();
            $masters = $client->root->hired()->doesntHave('subject')->pluck('email', 'id')->all();
            if($client->master) {
                $masters[$client->master->id] = $client->master->email;
            }

            if($client->ancestor !== null) {
                $managers = collect();

            }else{
                $managers = $users->where('role_id', 3)->pluck('email', 'id')->all();
            }

            $specifications = collect();

        }elseif(Auth::user()->isClientAdmin()) {
            $ancestors_all = Auth::user()->employer->network()->get();
            $ancestors_exceptions = $client->expand_network()->push($client);

            $ancestors = $ancestors_all->diff($ancestors_exceptions)->pluck('name', 'id')->all();

            $masters = $client->root->hired()->doesntHave('subject')->pluck('email', 'id')->all();
            if($client->master) {
                $masters[$client->master->id] = $client->master->email;//add current master for auto pick in view
            }

            if(Auth::user()->employer->specification != null) {
                $specifications = Auth::user()->employer->specification->sub_specifications()->pluck('name', 'id')->all();
            }else {
                $specifications = collect();
            }

            $managers = collect();
        }elseif(Auth::user()->isManager()) {
            $ancestors = collect();
            $masters = collect();
            $managers = collect();
            $specifications = Auth::user()->specifications()->doesntHave('clients')->pluck('name', 'id')->all();
            if($client->specification) {
                $specifications[$client->specification->id] = $client->specification->name;
            }
//dd($specifications);
        }

        return view('clients.edit', compact('client', 'ancestors', 'masters', 'managers', 'specifications'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, Client $client, User $users)
    {
        if(Auth::user()->isCompanyAdmin()) {
            if($client->specification) {
                $specification_id = $client->specification->id;
            }else {
                $specification_id = null;
            }

        }

        if($client->id == request('ancestor_id')) {
            return back()->withInput()->with('message', 'The client can not be ancestor for itself, clear ancestor field');

        }elseif($client->id == $client->root->id && $request['ancestor_id'] != null) {
            return back()->withInput()->with('message', 'The client is root and can not have ancestor');

        }elseif($client->id != $client->root->id && $request['ancestor_id'] == null) {
            return back()->withInput()->with('message', 'The client can not be root, fill ancestor field');

        }elseif($request['manager_id'] != null && $client->ancestor !== null){
                return back()->withInput()->with('message', 'Not root client can not have manager');

        }else {
            $ancestors_exceptions = ($client->expand_network()->push($client))
                                    ->pluck('id')->all();
            $masters = $client->root->hired()->doesntHave('subject')->pluck('id')->all();
            if($client->master) {
                $masters[] = $client->master->id;//add current master for check
            }

            $managers = $users->where('role_id', 3)->pluck('id')->all();

            if($request['ancestor_id'] != null && in_array(request('ancestor_id'), $ancestors_exceptions)){
                return back()->withInput()->with('message', 'The given value can not be ancestor for the client');

            }elseif($request['master_id'] != null && !in_array(request('master_id'), $masters)){
                return back()->withInput()->with('message', 'This user can not be master');

            }elseif($request['manager_id'] != null && !in_array(request('manager_id'), $managers)){
                return back()->withInput()->with('message', 'This user can not be manager');
            }

            if($request['manager_id'] != null && Auth::user()->isClientAdmin()){
                return back()->withInput()->with('message', 'You can not appoint a manager');

            }
        }

        if(Auth::user()->isClientAdmin()) {
            $specifications = Auth::user()->employer->specification->sub_specifications()->pluck('id')->all();

            if($request->has('specification_id') && !in_array(request('specification_id'), $specifications)){
                    return back()->withInput()->with('message', 'This is not specification, created by you');
            }else {
                $specification_id = $request['specification_id'];
            }
        }elseif(Auth::user()->isManager()) {
            $specifications = Auth::user()->specifications()->doesntHave('clients')->pluck('id')->all();
            if($client->specification) {
                $specifications[] = $client->specification->id;
            }

                if($request->has('specification_id') && !in_array(request('specification_id'), $specifications)){
                    return back()->withInput()->with('message', 'This is not specification, created by you or specification already belongs to another client');

                }else{
                    if($client->specification && 
                        $client->specification->main_specification == null &&
                        $client->specification->id != $request['specification_id']) {
                        $client->specification->delete();
                    }

                    $client->specification_id = $request['specification_id'];
                    $client->save();
                    return redirect("/clients/$client->id");
                }

        }

        if($old_specification = $client->specification) {
            $old_specification_id = $old_specification->id;
        }elseif($old_specification = $client->root->specification) {
            $old_specification_id = $old_specification->id;
        }else {
            $old_specification_id = null;
        }

        $client->guid = request('guid');
        $client->one_c_id = request('one_c_id');
        $client->name = request('name');
        $client->code = request('code');
        $client->manager_id = request('manager_id');
        $client->master_id = request('master_id');
        $client->ancestor_id = request('ancestor_id');
        $client->specification_id = $specification_id;
        $client->phone_number = request('phone_number');
        $client->address = request('address');
        $client->main_contractor = request('main_contractor');
        $client->organization = request('organization');

        if($client->specification_id == null) {
            if($new_specification = $client->root->specification) {
                $new_specification_id = $new_specification->id;
            }else {
                $new_specification_id = null;
            }
        }else {
            $new_specification_id = $client->specification_id;
        }
//dd($old_specification_id, $new_specification_id);        
        if($new_specification_id != $old_specification_id) {//spec changed
            event(new NewSpecificationAppointedToClient($client, $new_specification_id, $old_specification_id));//root main ???
        }
//dd('stop');
        $client->save();//may be set to event listener in transaction(???)

        return redirect("/clients/$client->id");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

}
