<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Categories\CreateCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    protected $category;
    protected $product;

    public function __construct(Category $category, Product $product)
    {
        $this->category = $category;
        $this->product = $product;
    }
    public function index()
    {
        return new CategoryResource(Category::latest('id')->with('childrens')->with('parent')->paginate(5));
    }

    public function getProductsByCategoryId($categoryId)
    {
        $products = $this->product->getBy($categoryId);
        return $this->sentSuccessResponse($products, '', Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateCategoryRequest $request)
    {
        $dataCreate = $request->all();
        Category::create($dataCreate);

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
        $item = Category::findOrFail($id);
        return $this->sentSuccessResponse($item, '', Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id);
        $dataUpdate = $request->all();
        $category->update($dataUpdate);

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
        $category = Category::findOrFail($id);
        $category->delete();
        return $this->sentSuccessResponse('', 'Xoá thành công', Response::HTTP_OK);
    }
}
