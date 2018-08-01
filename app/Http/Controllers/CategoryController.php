<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use JWTAuth;

use App\Http\Resources\CategoryResource;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return [
            'status' => 'success',
            'data' => CategoryResource::collection(Category::with('exercises:name,type,category_id')->get())
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'name' => 'required',
        ]);

        $category = Category::create([
            'name' => $request->name,
        ]);

        return [
            'status' => 'success',
            'data' => new CategoryResource($category)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return [
            'status' => 'success',
            'data' => new CategoryResource($category)
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $category->name = $request->name;

        if($category->save()){
            return [
                'status' => 'success',
                'data' => new CategoryResource($category)
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if($category->delete()){
            return [
                'status' => 'success',
                'message' => 'Category deleted successfully'
            ];
        }
    }
}
