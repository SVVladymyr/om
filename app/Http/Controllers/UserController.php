<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests\UserRequest;
use App\Client;
use App\Role;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;

use App\MyClasses\CustomPaginator;

class UserController extends Controller
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
        $this->authorize('index', User::class);

        if(Auth::user()->isClientAdmin()) {
            $users = Auth::user()->employer->hired->sortBy('email');

        }elseif(Auth::user()->isCompanyAdmin()) {  
            $users = User::get()->sortBy('email');
        }

        $users = CustomPaginator::paginate($users, 10)->setPath(route('users'));

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Client $clients, Role $roles)
    {
        $this->authorize('create', User::class);

        if(Auth::user()->isClientAdmin()) {
            $employers = collect();
            $roles = $roles->whereIn('id', [4, 5])->pluck('name', 'id')->all();

        }elseif(Auth::user()->isCompanyAdmin()) {
            $employers = $clients->doesntHave('ancestor')->pluck('name', 'id')->all();
            $roles = $roles->whereIn('id', [2, 3, 4, 5])->pluck('name', 'id')->all();
        }

        return view('users.create', compact('roles', 'employers', 'clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request, Client $clients, Role $roles)
    {
        $this->authorize('create', User::class);

        if(Auth::user()->isClientAdmin()) {
            $employers = Auth::user()->employer()->pluck('id')->first();
            $roles = $roles->whereIn('id', [4, 5])->pluck('id')->all();
            $employer_id = Auth::user()->employer->id;

        }elseif(Auth::user()->isCompanyAdmin()) {
            $employers = $clients->doesntHave('ancestor')->pluck('id')->all();
            $roles = $roles->whereIn('id', [2, 3, 4, 5])->pluck('id')->all();
            $employer_id = $request['employer_id'];
        }

        if($request['employer_id'] != null && !in_array(request('employer_id'),$employers)) {
            return back()->withInput()->with('message', 'This client can not be employer');

        }elseif($request['employer_id'] != null && $request['role_id'] == 3) {
            return back()->withInput()->with('message', 'Manger can not be hired by client');

        }elseif($request['employer_id'] != null) {
            $root = $clients->where('id', request('employer_id'))->has('master')->first();
            if($root !== null && $request['role_id'] == 2) {
                return back()->withInput()->with('message', 'Admin already exists in this network, change users role or select another employer');
            }

        }elseif(!in_array(request('role_id'),$roles)) {
            return back()->withInput()->with('message', 'Yuo can not create user with this role');

        }elseif(!in_array(request('role_id'),$roles)) {
            return back()->withInput()->with('message', 'Yuo can not create user with this role');
        }    
        
        User::create([
            'first_name' => request('first_name'),
            'last_name' => request('last_name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'phone_number' => request('phone_number'),
            'show_price_status' => $request['show_price_status'],
            'role_id' => request('role_id'),
            'employer_id' => $employer_id,
        ]);

        $email = $request['email'];
        Mail::to($email)->send(new UserCreated($request));

        return redirect('users')->with('message', 'New user has been created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $this->authorize('view', $user);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, Role $roles, Client $clients)
    {
        $this->authorize('update', $user);

        if(Auth::user()->id == $user->id) {
            return back()->withInput()->with('message', 'You can not edit this user (yourself)');
        }

        if(Auth::user()->isClientAdmin()) {
            $roles = $roles->whereIn('id', [4, 5])->pluck('name', 'id')->all();

        }elseif(Auth::user()->isCompanyAdmin()) {
            $roles = $roles->whereIn('id', [2, 3, 4, 5])->pluck('name', 'id')->all();
            $employers = $clients->doesntHave('ancestor')->pluck('name', 'id')->all();
        }

        return view('users.edit', compact('user', 'roles', 'employers'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user, Role $roles, Client $clients)
    {
        if(Auth::user()->id == $user->id) {
            return back()->withInput()->with('message', 'You can not update this user (yourself)');
        }

        if(Auth::user()->isClientAdmin()) {
            $roles = $roles->whereIn('id', [4, 5])->pluck('id')->all();

        }elseif(Auth::user()->isCompanyAdmin()) {
            $roles = $roles->whereIn('id', [2, 3, 4, 5])->pluck('id')->all();
            $employers = $clients->doesntHave('ancestor')->pluck('id')->all();
        }

        if(!in_array(request('role_id'), $roles)) {
            return back()->withInput()->with('message', 'You can not give this role to user');
        }elseif($request['employer_id'] != null && !in_array(request('employer_id'),$employers)) {
            return back()->withInput()->with('message', 'This client can not be employer');

        }elseif($request['employer_id'] != null && $request['role_id'] == 3) {
            return back()->withInput()->with('message', 'Manager can not be hired by client');

        }elseif($request['employer_id'] != null) {
            $root = $clients->where('id', request('employer_id'))->has('master')->first();
            if($root !== null && $request['role_id'] == 2) {
                return back()->withInput()->with('message', 'Admin already exists in this network, change users role or select another employer');
            }

        }elseif($request['employer_id'] != null && Auth::user()->isClientAdmin()) {
            return back()->withInput()->with('message', 'You can not change employer');
        }       

        
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $user->phone_number = request('phone_number');
        $user->show_price_status = request('show_price_status');
        $user->role_id = request('role_id');
        
        if(Auth::user()->isCompanyAdmin()) {
            $user->employer_id = request('employer_id');
        }

        $user->save();

        return redirect('users')->with('message', 'User has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
