<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Resources\SetResource;
use JWTAuth;

use App\Exercise;
use App\Set;

class SetController extends Controller
{
    protected $user;

    //Constructor
    public function __construct() {
        $this->user = JWTAuth::parseToken()->authenticate();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return SetResource::collection(
            Set::with(
                [
                    'exercise', 
                    'user'
                ]
            )->where('user_id', '=', $this->user->id)->get()
        );
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
            'user_id' => $this->user->id,
            'total_weight' => $request->weight * $request->reps,
            'for_day' => $day
        ]);

        return new SetResource($set);
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
            ->where('user_id', $this->user->id)
            ->whereDate('created_at', $day)
            ->get();

        if(!empty($sets)) {
            return SetResource::collection($sets);
        } else {
            return response()->json([
                'message' => 'No sets for this date'
            ], 200);
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
        $set->weight = $request->weight;
        $set->reps = $request->reps;

        if($set->save()){
            return new SetResource($set);
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
        if($set->delete()) {
            return response()->json([
                'message' => 'Set deleted successfully'
            ], 200);
        }
    }
}
