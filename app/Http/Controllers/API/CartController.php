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
        $cart = $this->cart->getBy(auth()->user()->id);
        $cartProduct = $this->cartProduct->with(['product'])->where('cart_id', $cart->id)->get();
        if ($cartProduct) {
            foreach ($cartProduct as $item) {
                $item['total'] = $item->product->sale ? $item->product_quantity * $item->product->price * $item->product->sale
                    : $item->product_quantity * $item->product->price;
            }

            return $this->sentSuccessResponse($cartProduct, '', Response::HTTP_OK);
        } else {
            return $this->sentSuccessResponse('', 'Giỏ hàng của bạn đang trống', Response::HTTP_OK);
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
        if ($request->product_size) {
            $product = $this->product->find($request->product_id);
            if (!$product) {
                return $this->sentSuccessResponse('', 'Sản phẩm không tồn tại', Response::HTTP_BAD_REQUEST);
            }
            $cart = $this->cart->getCart(auth()->user()->id);
            $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
            if ($cartProduct) {
                $quantity = $cartProduct->product_quantity;
                $cartProduct->update(['product_quantity' => ($quantity + $request->product_quantity)]);
                return $this->sentSuccessResponse('', 'Đã thêm vào giỏ hàng', Response::HTTP_OK);
            } else {
                $dataCreate['cart_id'] = $cart->id;
                $dataCreate['product_size'] = $request->product_size;
                $dataCreate['product_quantity'] = $request->product_quantity;
                $dataCreate['product_price'] = $product->price;
                $dataCreate['product_id'] = $product->id;
                $cartProduct->create($dataCreate);
                return $this->sentSuccessResponse('', 'Đã thêm vào giỏ hàng', Response::HTTP_OK);
            }
        } else {
            return $this->sentSuccessResponse('', 'Bạn chưa chọn size', Response::HTTP_BAD_REQUEST);
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
        $product = $this->product->find($request->product_id);
        $cart = $this->cart->getCart(auth()->user()->id);
        $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
        $cartProduct->update(['product_quantity' => ($request->product_quantity)]);
        return $this->sentSuccessResponse('', 'Cập nhật giỏ hàng', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $product = $this->product->findOrFail($request->product_id);
        $cart = $this->cart->getCart(auth()->user()->id);
        $cartProduct = $this->cartProduct->getBy($cart->id, $product->id, $request->product_size);
        $cartProduct->delete();
        return $this->sentSuccessResponse('', 'Đã xoá khỏi giỏ hàng', Response::HTTP_OK);
    }

    public function processCheckout(CheckoutRequest $request)
    {

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
        $dataCreate['total'] = $total;
        $dataCreate['ship'] = 0;
        $order = $this->order->create($dataCreate);
        if (isset($dataCreate['code'])) {
            $coupon =  $this->coupon->where('name', $dataCreate['code'])->first();
            if ($coupon->count() > 0) {
                $coupon->users()->attach(auth()->user()->id, ['value' => $coupon->value, 'order_id' => $order->id]);
            }
            $couponTotal = $total - ($total * $coupon->value / 100);
            $order->update(['total' => $couponTotal]);
        }
        foreach ($cartProduct as $item) {
            $data['product_size'] = $item->product_size;
            $data['product_quantity'] = $item->product_quantity;
            $data['product_price'] = $item->product->price;
            $data['product_id'] = $item->product->id;
            $data['order_id'] = $order->id;
            ProductOrder::create($data);
        }
        $cart->products()->delete();

        return $this->sentSuccessResponse($cartProduct, 'Đặt hàng thành công', Response::HTTP_OK);
    }
}
