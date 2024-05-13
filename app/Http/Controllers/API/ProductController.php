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
        return new ProductResource(Product::latest('id')->paginate(8));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $dataCreate = $request->except('sizes');
        $sizes = $request->sizes ? $request->sizes : [];

        $product = $this->product->create($dataCreate);
        $product->categories()->attach($dataCreate['category_ids']);

        $sizeArray = [];

        foreach ($sizes as $size) {
            $sizeArray[] = ['size' => $size['size'], 'quantity' => $size['quantity'], 'product_id' => $product->id];
        }
        $this->productDetail->insert($sizeArray);
        return $this->sentSuccessResponse('', 'Tạo mới thành công', Response::HTTP_OK);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->with('details')->findOrFail($id);
        return $this->sentSuccessResponse($product, '', Response::HTTP_OK);
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

        return $this->sentSuccessResponse('', 'Cập nhật thành công', Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return $this->sentSuccessResponse('', 'Xoá thành công', Response::HTTP_OK);
    }
}
