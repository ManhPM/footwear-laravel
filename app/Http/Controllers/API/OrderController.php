<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
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
        try {
            return new OrderResource(Order::latest('id')->paginate(5));
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    public function getAllOrderUser()
    {
        try {
            return new OrderResource(Order::latest('id')->where('user_id', auth()->user()->id)->paginate(5));
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getDetailOrder($id)
    {
        try {
            $order = $this->order->with(['products', 'payment_method', 'coupon'])->find($id);
            return $this->sentSuccessResponse($order, '', Response::HTTP_OK);
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
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
        try {
            $order = $this->order->with('products')->find($id);
            if (!$order) {
                $message = $this->getMessage('INVOICE_NOTFOUND');
                return response()->json(['message' => $message], 404);
            }
            if ($order->status != 'pending') {
                $message = $this->getMessage('CONFIRM_ERROR');
                return response()->json(['message' => $message], 400);
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
                $order->payment_status = 'paid';
                $order->save();

                $message = $this->getMessage('CONFIRM_SUCCESS');
                return response()->json(['message' => $message], 200);
            } else {
                $message = $this->getMessage('INPUT_QUANTITY_ERROR3');
                return response()->json(['message' => $message], 400);
            }
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }

    public function cancelOrder($id)
    {
        try {
            $order = $this->order->with('products')->find($id);
            if (!$order) {
                $message = $this->getMessage('INVOICE_NOTFOUND');
                return response()->json(['message' => $message], 404);
            }
            if ($order->status != 'pending') {
                $message = $this->getMessage('CANCEL_INVOICE_ERROR');
                return response()->json(['message' => $message], 400);
            }
            $order->status = 'canceled';
            $order->save();

            $message = $this->getMessage('CANCEL_SUCCESS');
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
    public function destroy($id)
    {
    }
}
