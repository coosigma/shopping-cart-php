<?php
declare (strict_types = 1);

use Illuminate\Support\Facades\Route;
use App\Cart;

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

// Product data
$products = [
    ["name" => "Sledgehammer", "price" => 125.75],
    ["name" => "Axe", "price" => 190.50],
    ["name" => "Bandsaw", "price" => 562.131],
    ["name" => "Chisel", "price" => 12.9],
    ["name" => " Hacksaw", "price" => 18.45],
];

// Create model
$cart = new Cart($products);

// Send data to the template
Route::get('/', function () use ($cart) {
    return view('welcome', ['products' => $cart->listProducts(), 'cart' => $cart->listCart()]);
});

// Add product to cart
Route::get('/add/{id}', function ($id) use ($cart) {
    $cart->add($id);
    return redirect("/");
});

// Remove product from cart
Route::get('/remove/{id}', function ($id) use ($cart) {
    $cart->remove($id);
    return redirect("/");
});
