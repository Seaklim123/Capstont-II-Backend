<?php

namespace App\Http\Controllers\Backend\category;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\category\CreateCategoryRequest;
use App\Http\Requests\backend\category\UpdateCategoryRequest;
use App\Http\Resources\Backend\category\CategoryResource;
use App\Services\CategoryServices;

class CategoryController extends Controller
{

    protected $categoryServices;
    public function __construct(CategoryServices $categoryServices){
        $this->categoryServices = $categoryServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $category = $this->categoryServices->allCategroy();
        return CategoryResource::collection($category);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request)
    {
        //
        $validated = $request->validated();

        $category = $this->categoryServices->createCategory($validated);
        return new CategoryResource($category);


    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        //
        $category = $this->categoryServices->findCategory($id);
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request,int $id )
    {
        //
        $data = $request->validated();
        // $data['image_path'] = $request->file('image_path') ?? null;

        $category = $this->categoryServices->updateCategory($id, $data);

        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        //
        $this->categoryServices->deleteCategory($id);
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
