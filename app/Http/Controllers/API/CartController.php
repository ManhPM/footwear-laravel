<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\CheckoutRequest;
use App\Models\Cart;
use App\Models\CartProduct;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $product;
    protected $coupon;
    protected $cart;
    protected $cartProduct;
    protected $order;

    public function __construct(Coupon $coupon, Product $product, Cart $cart, CartProduct $cartProduct, Order $order)
    {
        $this->product = $product;
        $this->coupon = $coupon;
        $this->cart = $cart;
        $this->cartProduct = $cartProduct;
        $this->order = $order;
    }

    public function index()
    {
        try {
            $cart = $this->cart->getBy(auth()->user()->id);
            $cartProduct = $this->cartProduct->with(['product'])->where('cart_id', $cart->id)->get();
            if ($cartProduct) {
                foreach ($cartProduct as $item) {
                    $item['total'] = $item->product->sale ? $item->product_quantity * $item->product->price * $item->product->sale
                        : $item->product_quantity * $item->product->price;
                }

                return $this->sentSuccessResponse($cartProduct, '', Response::HTTP_OK);
            } else {
                $message = $this->getMessage('CHECKOUT_ERROR2');
                return $this->sentSuccessResponse('', $message, Response::HTTP_OK);
            }
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            if ($request->product_size) {
                $product = $this->product->find($request->product_id);
                if (!$product) {
                    $message = $this->getMessage('PRODUCT_NOTFOUND');
                    return response()->json(['message' => $message], 404);
                }
                $cart = $this->cart->getCart(auth()->user()->id);
                $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
                if ($cartProduct) {
                    $quantity = $cartProduct->product_quantity;
                    $cartProduct->update(['product_quantity' => ($quantity + $request->product_quantity)]);

                    $message = $this->getMessage('ADD_TO_CART_SUCCESS');
                    return response()->json(['message' => $message], 200);
                } else {
                    $dataCreate['cart_id'] = $cart->id;
                    $dataCreate['product_size'] = $request->product_size;
                    $dataCreate['product_quantity'] = $request->product_quantity;
                    $dataCreate['product_price'] = $product->price;
                    $dataCreate['product_id'] = $product->id;
                    $this->cartProduct->create($dataCreate);
                    $message = $this->getMessage('ADD_TO_CART_SUCCESS');
                    return response()->json(['message' => $message], 200);
                }
            } else {
                $message = $this->getMessage('ADD_TO_CART_ERROR');
                return response()->json(['message' => $message], 400);
            }
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $product = $this->product->find($request->product_id);
            $cart = $this->cart->getCart(auth()->user()->id);
            $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
            $cartProduct->update(['product_quantity' => ($request->product_quantity)]);

            $message = $this->getMessage('UPDATE_SUCCESS');
            return response()->json(['message' => $message], 200);
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $product = $this->product->findOrFail($request->product_id);
            $cart = $this->cart->getCart(auth()->user()->id);
            $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
            $cartProduct->delete();

            $message = $this->getMessage('DELETE_SUCCESS');
            return response()->json(['message' => $message], 200);
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    public function processCheckout(CheckoutRequest $request)
    {
        try {
            $dataCreate = $request->all();
            $cart = $this->cart->getBy(auth()->user()->id);
            $total = 0;
            $cartProduct = $this->cartProduct->with(['product'])->where('cart_id', $cart->id)->get();
            foreach ($cartProduct as $item) {
                $total += $item->product->sale ? $item->product_quantity * $item->product->price * (100 - $item->product->sale) / 100
                    : $item->product_quantity * $item->product->price;
            }
            $dataCreate['customer_name'] = auth()->user()->name;
            $dataCreate['customer_email'] = auth()->user()->email;
            $dataCreate['customer_phone'] = auth()->user()->phone;
            $dataCreate['customer_address'] = auth()->user()->address;
            $dataCreate['user_id'] = auth()->user()->id;
            $dataCreate['status'] = 'pending';
            $dataCreate['payment_status'] = 'unpaid';
            $dataCreate['total'] = $total;
            $dataCreate['ship'] = 0;
            if (isset($dataCreate['code'])) {
                $coupon =  $this->coupon->where('name', $dataCreate['code'])->first();
                $dataCreate['coupon_id'] = $coupon->id;
                $dataCreate['total'] = $dataCreate['total'] * (100 - $coupon->value) / 100;
            }
            $order = $this->order->create($dataCreate);
            foreach ($cartProduct as $item) {
                $data['product_size'] = $item->product_size;
                $data['product_quantity'] = $item->product_quantity;
                $data['product_price'] = $item->product->price;
                $data['product_id'] = $item->product->id;
                $data['order_id'] = $order->id;
                ProductOrder::create($data);
            }
            $cart->products()->delete();
            $message = $this->getMessage('CHECKOUT_SUCCESS');
            return $this->sentSuccessResponse($order, $message, Response::HTTP_OK);
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }
}
