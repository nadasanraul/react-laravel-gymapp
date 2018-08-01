<?php

namespace App\Http\Controllers;

use App\Exercise;
use App\Category;
use JWTAuth;
use Illuminate\Http\Request;

use App\Http\Resources\ExerciseResource;

class ExerciseController extends Controller
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
            'data' => ExerciseResource::collection(Exercise::with('category')->get())
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
            'type' => 'required',
            'category' => 'required'
        ]);

        $category = Category::where('name', $request->category)->first();

        $exercise = Exercise::create([
            'name' => $request->name,
            'type' => $request->type,
            'category_id' => $category->id
        ]);

        return [
            'status' => 'success',
            'data' => new ExerciseResource($exercise)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {

        return [
            'status' => 'success',
            'data' => new ExerciseResource($exercise)
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        $category = Category::where('name', $request->category)->first();

        $exercise->name = $request->name;
        $exercise->type = $request->type;
        $exercise->category_id = $category->id;

        if($exercise->save()){
            return [
                'status' => 'success',
                'data' => new ExerciseResource($exercise)
            ];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        if($exercise->delete()){

            return [
                'status' => 'success',
                'message' => 'Exercise deleted successfully'
            ];
        } 
    }
}
