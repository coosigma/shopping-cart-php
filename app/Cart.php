<?php
declare (strict_types = 1);

namespace App;

class Cart
{
    protected $products;
    public function __construct($products)
    {
        $this->products = $products;
    }
    // Get Product list
    public function listProducts(): array
    {
        // Process all elements in the array
        return array_map(function (&$product) {
            // Set to two decimal places
            $product['price'] = number_format((float) $product['price'], 2, '.', '');
            return $product;
        }, $this->products);
    }
    // Get Cart list
    public function listCart(): array
    {
        // Get cart object from cookie
        $cart = $this->getCart();
        $products = $this->products;
        $subtotal = 0;
        // Generate Cart data and Calculate "total" and "subtotal" from all products in the cart
        // (Cart stores product IDs and their quantities)
        $cartInfo = array_map(function ($quantity, $id) use ($products, &$subtotal) {
            $product = $products[$id];
            $total = $product["price"] * $quantity;
            $subtotal += $total;
            return ["id" => $id, "name" => $product["name"], "price" => number_format((float) $product['price'], 2, '.', ''), "quantity" => $quantity, "total" => number_format((float) $total, 2, '.', '')];
        }, $cart, array_keys($cart));
        return ['subtotal' => number_format((float) $subtotal, 2, '.', ''), 'cartInfo' => $cartInfo];
    }
    // get cart object from cookie
    public function getCart()
    {
        // return cart data if it exists, otherwise a empty array
        return array_key_exists("cart", $_COOKIE) ? json_decode($_COOKIE["cart"], true) : [];
    }
    // store cart into cookie
    public function store($cart)
    {
        // Set cart data to cookie and expiration time
        setcookie("cart", json_encode($cart), time() + 3600, "/");
    }
    // add 1 product into cart
    public function add($productId)
    {
        $cart = $this->getCart();
        // Add quantity of the product if it exists in the cart, otherwise set it to 1
        if (array_key_exists($productId, $cart)) {
            $cart[$productId]++;
        } else {
            $cart[$productId] = 1;
        }
        // Store cart to cookie
        $this->store($cart);
    }
    // remove product from cart
    public function remove($productId)
    {
        $cart = $this->getCart();
        // Remove product from cart (if it exists)
        if (array_key_exists($productId, $cart)) {
            unset($cart[$productId]);
        }
        $this->store($cart);
    }
}
