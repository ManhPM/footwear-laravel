<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $category;
    protected $product;
    protected $productDetail;

    public function __construct(Category $category, Product $product, ProductDetail $productDetail)
    {
        $this->category = $category;
        $this->product = $product;
        $this->productDetail = $productDetail;
    }

    public function index()
    {
        try {
            return new ProductResource(Product::latest('id')->paginate(8));
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
    public function store(CreateProductRequest $request)
    {
        try {
            $dataCreate = $request->except('sizes');
            $sizes = $request->sizes ? $request->sizes : [];

            $product = $this->product->create($dataCreate);
            $product->categories()->attach($dataCreate['category_ids']);

            $sizeArray = [];

            foreach ($sizes as $size) {
                $sizeArray[] = ['size' => $size['size'], 'quantity' => $size['quantity'], 'product_id' => $product->id];
            }
            $this->productDetail->insert($sizeArray);
            $message = $this->getMessage('CREATE_SUCCESS');
            return response()->json(['message' => $message], 200);
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
    public function show($id)
    {
        try {
            $product = $this->product->with('details')->findOrFail($id);
            return $this->sentSuccessResponse($product, '', Response::HTTP_OK);
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
    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $dataUpdate = $request->except('sizes');
            $product = $this->product->findOrFail($id);
            $product->update($dataUpdate);

            $sizes = $request->sizes ? $request->sizes : [];

            $product->categories()->sync($dataUpdate['category_ids'] ?? []);

            $sizeArray = [];
            foreach ($sizes as $size) {
                $sizeArray[] = ['size' => $size['size'], 'quantity' => $size['quantity'], 'product_id' => $product->id];
            }
            $product->details()->delete();
            $this->productDetail->insert($sizeArray);

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
    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();
            $message = $this->getMessage('DELETE_SUCCESS');
            return response()->json(['message' => $message], 200);
        } catch (\Throwable $th) {
            $message = $this->getMessage('INTERNAL_SERVER_ERROR');
            return response()->json(['message' => $message], 500);
        }
    }
}
