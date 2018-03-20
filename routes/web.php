<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('agreement', function () {
    return view('agreement.agreement');;
});

Route::post('reports', 'HomeController@reports')->name('reports');//done

Route::post('request', 'Auth\RegisterController@guest_interested')->name('request');//done

Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');//done
Route::post('/', 'Auth\LoginController@login');//done
Route::get('logout', 'Auth\LoginController@logout');//done

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password_reset');//done
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');//done
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');//done
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.request');//done

Route::get('home', 'HomeController@index')->name('home');//done

Route::get('users', 'UserController@index')->name('users');//done
Route::get('users/create', 'UserController@create');//done
Route::post('users', 'UserController@store');//done
Route::get('users/edit/{user}', 'UserController@edit');//done
Route::post('users/update/{user}', 'UserController@update');//done
Route::get('users/{user}', 'UserController@show');//done

Route::get('clients', 'ClientController@index')->name('clients');//done
Route::get('clients/create', 'ClientController@create');//done
Route::post('clients', 'ClientController@store');//done
Route::get('clients/edit/{client}', 'ClientController@edit');//done
Route::post('clients/update/{client}', 'ClientController@update');//done
Route::get('clients/{client}', 'ClientController@show');//done
Route::get('clients/{client}/network', 'ClientController@network')->name('network');//done

Route::get('specifications', 'SpecificationController@index')->name('specifications');//done
Route::get('specifications/create', 'SpecificationController@create');//done
Route::post('specifications', 'SpecificationController@store');//done
Route::get('specifications/edit/{specification}', 'SpecificationController@edit');//done
Route::post('specifications/update/{specification}', 'SpecificationController@update');
Route::get('specifications/{specification}', 'SpecificationController@show');//done
Route::post('specifications/{specification}/upload', 'SpecificationController@upload');//done
Route::get('specifications/delete/{specification}', 'SpecificationController@destroy');//done

Route::get('clients/{client}/limits', 'LimitController@index');//done
Route::get('clients/{client}/limits/set', 'LimitController@set_limits');//done
Route::post('clients/{client}/limits', 'LimitController@fill_limits');//done

Route::post('clients/{client}/limit_increase_request', 'LimitIncreaseController@limit_increase_request')->name('limit_increase');
Route::get('clients/{client}/limit_increases', 'LimitIncreaseController@index')->name('limit_increases');
Route::post('clients/{client}/limit_increases', 'LimitIncreaseController@set_statuses');

Route::get('cost_items', 'CostItemController@index')->name('cost_items');//done
Route::get('cost_items/create', 'CostItemController@create');//done
Route::post('cost_items', 'CostItemController@store');//done
Route::get('cost_items/edit/{cost_item}', 'CostItemController@edit');//done
Route::post('cost_items/update/{cost_item}', 'CostItemController@update');//done
Route::get('cost_items/{cost_item}', 'CostItemController@show');//done
Route::get('cost_items/delete/{cost_item}', 'CostItemController@destroy');//done
Route::get('set_product_cost_items', 'CostItemController@set_product_cost_items');//done
Route::post('fill_product_cost_items', 'CostItemController@fill_product_cost_items');//done

Route::get('clients/{client}/orders', 'OrderController@index')->name('orders');//done
Route::get('orders', 'OrderController@user_index')->name('user_orders');//done
Route::post('orders', 'OrderController@user_index');//done
Route::get('clients/{client}/orders/create', 'OrderController@create');//done
Route::post('clients/{client}/orders', 'OrderController@store');//done
Route::get('orders/edit/{order}', 'OrderController@edit');//done
Route::post('orders/update/{order}', 'OrderController@update');//done
Route::get('orders/{order}', 'OrderController@show');//done
Route::post('mass_set_status_date', 'OrderController@mass_set_status_date')->name('status');//done

//1c check
//refactor products method in speciification
