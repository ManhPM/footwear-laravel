<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $order;
    protected $productOrder;

    public function __construct(Order $order, ProductOrder $productOrder)
    {
        $this->order = $order;
        $this->productOrder = $productOrder;
    }

    public function index()
    {
        return new OrderResource(Order::latest('id')->paginate(5));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->sentSuccessResponse('', '', Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailOrder($id)
    {
        $order = $this->order->with('products')->find($id);
        return $this->sentSuccessResponse($order, '', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function confirmOrder($id)
    {
        $order = $this->order->with('products')->find($id);
        if (!$order) {
            return $this->sentSuccessResponse('', 'Đơn hàng không tồn tại', Response::HTTP_BAD_REQUEST);
        }
        if ($order->status != 'pending') {
            return $this->sentSuccessResponse('', 'Đơn hàng đã được xác nhận rồi', Response::HTTP_BAD_REQUEST);
        }

        $productOrder = $this->productOrder->where('order_id', $order->id)->get();

        $isEnough = 1;
        foreach ($productOrder as $item) {
            $productDetail = ProductDetail::where('product_id', $item->product_id)->where('size', $item->product_size)->first();
            if ($productDetail->quantity < $item->product_quantity) {
                $isEnough = 0;
            }
        }

        if ($isEnough) {
            foreach ($productOrder as $item) {
                $productDetail = ProductDetail::where('product_id', $item->product_id)->where('size', $item->product_size)->first();
                $productDetail->decrement('quantity', $item->product_quantity);
            }

            $order->status = 'confirmed';
            $order->save();

            return $this->sentSuccessResponse('', 'Nhận đơn thành công', Response::HTTP_OK);
        } else {
            return $this->sentSuccessResponse('', 'Hàng trong kho không đủ, không thể nhận đơn', Response::HTTP_BAD_REQUEST);
        }
    }

    public function cancelOrder($id)
    {
        $order = $this->order->with('products')->find($id);
        if (!$order) {
            return $this->sentSuccessResponse('', 'Đơn hàng không tồn tại', Response::HTTP_BAD_REQUEST);
        }
        if ($order->status != 'pending') {
            return $this->sentSuccessResponse('', 'Đơn hàng không thể huỷ', Response::HTTP_BAD_REQUEST);
        }
        $order->status = 'canceled';
        $order->save();

        return $this->sentSuccessResponse('', 'Huỷ đơn thành công', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
    }
}
