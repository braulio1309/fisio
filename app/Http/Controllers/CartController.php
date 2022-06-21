<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{

    public function show($id, Request $request)
    {

        $cart = Cart::where(['user_id' => $id])->with('items')->first();

        if ($cart) {
            $data = CartItem::where(['cart_id' => $cart->id])
                            ->with('product', function($q) {
                                $q->select('id', 'category_id', 'name', 'cost', 'TaxNet', 'image', 'stock_alert', 'note');
                            })
                            ->get(['id', 'cart_id', 'product_id', 'quantity']);
    
            return $this->sendResponse($data->toArray(), 'Carrito obtenido.');

        } else {
            return $this->sendResponse([], 'Carrito obtenido.');
        }

    }

    public function addProducts(Cart $cart, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'user_id' => 'required',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $cart = Cart::where(['user_id' => $request->get('user_id')])->first();

        if (!$cart) {
            $newCart = new Cart();
            $newCart->id = md5(uniqid(rand(), true));
            $newCart->user_id = $request->get('user_id');
            $newCart->save();
            $cartID = $newCart->id;
        } else {
            $cartID = $cart->id;
        }

        $productID = $request->input('product_id');
        $quantity = $request->input('quantity');


            try { $Product = Product::findOrFail($productID);} catch (ModelNotFoundException $e) {
                return response()->json([
                    'message' => 'The Product you\'re trying to add does not exist.',
                ], 404);
            }

            $cartItem = CartItem::where(['cart_id' => $cartID, 'product_id' => $productID])->first();
            if ($cartItem) {
                $cartItem->quantity = $quantity;
                CartItem::where(['cart_id' => $cartID, 'product_id' => $productID])->update(['quantity' => $quantity]);
            } else {
                CartItem::create(['cart_id' => $cartID, 'product_id' => $productID, 'quantity' => $quantity]);
            }

            return response()->json(['message' => 'The Cart was updated with the given product information successfully'], 200);
    }

    public function deleteItemCart (Request $request) {

        $productID = $request->input('product_id');
        $cart = Cart::where(['user_id' => $request->input('user_id')])->first();
        $cartItem = CartItem::where(['cart_id' => $cart->id, 'product_id' => $productID])->first();
        $item = CartItem::findOrFail($cartItem->id);
        $item->delete();
        return $this->sendResponse($item->toArray(), 'Producto eliminado del carrito');
    }

    public function deleteCart (Request $request) {
        $cart = Cart::where(['user_id' => $request->get('user_id')])->first();
    }

    public function uploadCartLocal(Request $request) {

        $cart = Cart::where(['user_id' => $request->get('user_id')])->first();

        if (!$cart) {
            $newCart = new Cart();
            $newCart->id = md5(uniqid(rand(), true));
            $newCart->user_id = $request->get('user_id');
            $newCart->save();
            $cartID = $newCart->id;
        } else {
            $cartID = $cart->id;
        }
        $products = json_decode($request->get('products'));

        foreach ($products as $key => $value) {
            $cartItem = CartItem::where(['cart_id' => $cartID, 'product_id' => $value->id])->first();

            if ($cartItem) {
                $cartItem->quantity = $cart->quantity;
                CartItem::where(['cart_id' => $cartID, 'product_id' => $value->id])->update(['quantity' => $cart->quantity]);
            } else {
                CartItem::create(['cart_id' => $cartID, 'product_id' => $value->id, 'quantity' => $value->quantity]);
            }
        }

        return response()->json(['message' => 'Productos del carrito registrados'], 200);
    }
}
