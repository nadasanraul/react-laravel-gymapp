<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Set;
use App\Workout;
use JWTAuth;

class WorkoutController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workouts = [];
        
        $days = array_unique(Set::select('for_day')
            ->where('user_id', auth()->user()->id)
            ->pluck('for_day')
            ->toArray());

        foreach($days as $day) {
            $workout = new Workout($day);
            array_push($workouts, $workout->getWorkout());
        }

        return [
            'status' => 'success',
            'data' => $workouts,
        ];
    }


    /**
     * Display the specified resource.
     *
     * @param  string  $day
     * @return \Illuminate\Http\Response
     */
    public function show($day)
    {
        $workout = new Workout($day);

        return [
            'status' => 'success',
            'data' => $workout->getWorkout()
        ];
    }

}
