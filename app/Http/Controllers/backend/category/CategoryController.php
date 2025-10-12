<?php

namespace App\Http\Controllers\backend\category;

use App\Http\Controllers\Controller;
use App\Http\Requests\backend\category\CreateCategoryRequest;
use App\Http\Resources\Backend\category\CategoryResource;
use App\Services\CategoryServices;
use Illuminate\Http\Request;

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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
