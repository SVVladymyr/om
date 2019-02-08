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
    public function index(Request $request)
    {

        $this->authorize('index', User::class);

        if(Auth::user()->isClientAdmin()) {
            $users = Auth::user()->employer->hired->sortBy('email');

        }elseif(Auth::user()->isCompanyAdmin()) {
            $users = User::get()->sortBy('email');
        }

        if(!empty($request->all())){

            $search_arr = $request->all();

            foreach ($search_arr as $key=>$value){
                if($key=='role'&&!empty($value)){
                    $role_ids = Role::where('name','like','%'.$value.'%')->pluck('id')->toArray();

                    if(empty($role_ids))
                        $search_arr[$key] = ['empty'];
                    else
                        $search_arr[$key] = $role_ids;

                }
                else if($key=='main'&&!empty($value)){
                    $employer_ids = Client::where('name','like','%'.$value.'%')->pluck('id')->toArray();

                    if(empty($employer_ids))
                        $search_arr[$key] = ['empty'];
                    else
                        $search_arr[$key] = $employer_ids;

                }
                else if($key =='subdivision'&&!empty($value)){
                    $user_ids = Client::where('name','like','%'.$value.'%')->pluck('master_id')->toArray();

                    if(empty($user_ids))
                        $search_arr[$key] = ['empty'];
                    else
                        $search_arr[$key] = $user_ids;
                }
                else if($key=='email'&&!empty($value)){

                }
                else
                    unset($search_arr[$key]);
            }


            if(Auth::user()->isClientAdmin()) {

                $employer = Auth::user()->employer;

                $users = User::where('employer_id','=',$employer->id)

                    ->where(function($query) use ($search_arr){

                        foreach($search_arr as $key=>$value){

                            if($key=='email'&&!empty($value))
                                $query->Where($key, 'LIKE', '%'.$value.'%');

                            else if($key=='role'&&!empty($value))
                                $query->WhereIn('role_id', $value);

                            else if($key=='main'&&!empty($value))
                                $query->WhereIn('employer_id', $value);

                            else if($key=='subdivision'&&!empty($value))
                                $query->WhereIn('id',$value);

                        }
                    })
                    ->get()
                    ->sortBy('email');

            }elseif(Auth::user()->isCompanyAdmin()) {

                $users = User::where(function($query) use($search_arr){

                    foreach($search_arr as $key=>$value){

                        if($key=='email'&&!empty($value))
                            $query->Where($key, 'LIKE', '%'.$value.'%');

                        else if($key=='role'&&!empty($value))
                            $query->WhereIn('role_id', $value);

                        else if($key=='main'&&!empty($value))
                            $query->WhereIn('employer_id', $value);

                        else if($key=='subdivision'&&!empty($value))
                            $query->WhereIn('id',$value);

                    }

                })->get()->sortBy('email');
            }
        }
//        dump($users[0]);

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
//        dd($request);
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
            return back()->withInput()->with('message', 'Этот клиент не может быть работодателем');

        }elseif($request['employer_id'] != null && $request['role_id'] == 3) {
            return back()->withInput()->with('message', 'Менеджер не может быть нанятым клиентом');

        }elseif($request['employer_id'] != null) {
            $root = $clients->where('id', request('employer_id'))->has('master')->first();
            if($root !== null && $request['role_id'] == 2) {
                return back()->withInput()->with('message', 'Admin уже существует, измените роль пользователя или выберите другого работодателя');
            }

        }elseif(!in_array(request('role_id'),$roles)) {
            return back()->withInput()->with('message', 'Вы не можете создать пользователя с этой ролью');

        }elseif(!in_array(request('role_id'),$roles)) {
            return back()->withInput()->with('message', 'Вы не можете создать пользователя с этой ролью');
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

        return redirect('users')->with('message', 'Новый пользователь был создан');
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
            return back()->withInput()->with('message', 'Вы не можете изменить этого пользователя');
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
//        dd($request);
        if(Auth::user()->id == $user->id) {
            return back()->withInput()->with('message', 'Вы не можете изменить этого пользователя');
        }

        if(Auth::user()->isClientAdmin()) {
            $roles = $roles->whereIn('id', [4, 5])->pluck('id')->all();

        }elseif(Auth::user()->isCompanyAdmin()) {
            $roles = $roles->whereIn('id', [2, 3, 4, 5])->pluck('id')->all();
            $employers = $clients->doesntHave('ancestor')->pluck('id')->all();
        }

        if(!in_array(request('role_id'), $roles)) {
            return back()->withInput()->with('message', 'Вы не можете назначить эту роль пользователю');
        }elseif($request['employer_id'] != null && !in_array(request('employer_id'),$employers)) {
            return back()->withInput()->with('message', 'Этот клиент не может быть работодателем');

        }elseif($request['employer_id'] != null && $request['role_id'] == 3) {
            return back()->withInput()->with('message', 'Менеджер не может быть нанятым клиентом');

        }elseif($request['employer_id'] != null) {
            $root = $clients->where('id', request('employer_id'))->has('master')->first();
            if($root !== null && $request['role_id'] == 2) {
                return back()->withInput()->with('message', 'Admin уже существует, измените роль пользователя или выберите другого работодателя');
            }

        }elseif($request['employer_id'] != null && Auth::user()->isClientAdmin()) {
            return back()->withInput()->with('message', 'Вы не можете изменить работодателя');
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

        return redirect('users')->with('message', 'Пользователь был обновлен');
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
