<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\SetResource;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Http\Exceptions\HttpResponseException;
use JWTAuth;

use App\Exercise;
use App\Set;

class SetController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sets = Set::with(['exercise', 'user'])->where('user_id', '=', auth()->user()->id)->get();

        return [
            'status' => 'success',
            'data' => SetResource::collection($sets)
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Exercise $exercise, $day, Request $request)
    {
        $this->validate(request(), [
            'weight' => 'required',
            'reps' => 'required',
        ]);

        $set = Set::create([
            'exercise_id' => $exercise->id,
            'weight' => $request->weight,
            'reps' => $request->reps,
            'user_id' => auth()->user()->id,
            'total_weight' => $request->weight * $request->reps,
            'for_day' => $day
        ]);

        return [
            'status' => 'success',
            'data' => new SetResource($set)
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise, $day)
    {
        $sets = Set::with(['exercise', 'user'])
            ->where('exercise_id', $exercise->id)
            ->where('user_id', auth()->user()->id)
            ->whereDate('created_at', $day)
            ->get();

        if(count($sets) > 0) {
            return [
                'status' => 'success',
                'data' => SetResource::collection($sets)
            ];
        } else {
            return [
                'status' => 'success',
                'message' => 'No sets for this date'
            ];
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Set $set)
    {
        if($set->user_id === auth()->user()->id) {
            $set->weight = $request->weight;
            $set->reps = $request->reps;
    
            if($set->save()){
                return [
                    'status' => 'success',
                    'data' => new SetResource($set)
                ];
            }
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized request',
            ], 401);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Set $set)
    {
        if($set->id === auth()->user()->id){
            $set->delete();

            return [
                'status' => 'success',
                'message' => 'Set deleted successfully'
            ];
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized request',
            ], 401);
        }
    }
}
