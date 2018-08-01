<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Set;
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

        $uniqueDays = [];
        $workouts = [];

        $sets = Set::with(['exercise'])
            ->where('user_id', auth()->user()->id)
            ->orderBy('created_at', 'asc')
            ->get();
        
        $days = Set::select('for_day')
            ->where('user_id', auth()->user()->id)
            ->get();


        foreach($days as $day) {
            if(!in_array($day->for_day, $uniqueDays)) {
                array_push($uniqueDays, $day->for_day);
            }
        }


        foreach($uniqueDays as $day) {
            $workout = (object)[
                'day' => $day,
                'exercises' => []
            ];
            array_push($workouts, $workout);
        }

        foreach($workouts as $workout) {
            $workout_exercises = [];
            foreach($sets as $set) {
                if($set->for_day === $workout->day && !in_array($set->exercise->name, $workout_exercises)) {
                    array_push($workout_exercises, $set->exercise->name);
                } 
            }
            foreach($workout_exercises as $item) {
                $exercise = (object)[
                    'name' => $item,
                    'sets' => [],
                    'total_weight' => 0,
                    'total_reps' => 0
                ];
                array_push($workout->exercises, $exercise);
            }
        }

        foreach($sets as $set) {
            foreach($workouts as $workout) {
                if($set->for_day === $workout->day) {
                    foreach($workout->exercises as $exercise) {
                        if($set->exercise->name === $exercise->name) {
                            array_push($exercise->sets, (object)[
                                'id' => $set->id,
                                'weight' => $set->weight,
                                'reps' => $set->reps
                            ]);
                            $exercise->total_weight += $set->total_weight;
                            $exercise->total_reps += $set->reps;
                        }
                    }
                }
            }
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
        // $user = User::find(3);
        $sets = Set::with(['exercise'])
            ->where('user_id', auth()->user()->id)
            ->where('for_day', $day)
            ->orderBy('created_at', 'asc')
            ->get();

        $exercises  = [];
        $workout_exercises = [];

        foreach($sets as $set) {
            if(!in_array($set->exercise->name, $workout_exercises)) {
                array_push($workout_exercises, $set->exercise->name);
            }
        }

        foreach($workout_exercises as $item) {
            $exercise = (object)[
                'name' => $item,
                'sets' => [],
                'total_weight' => 0,
                'total_reps' => 0
            ];
            array_push($exercises, $exercise);
        }

        
        foreach($sets as $set) {
            foreach($exercises as $exercise) {
                if($set->exercise->name === $exercise->name) {
                    array_push($exercise->sets, (object)[
                        'id' => $set->id,
                        'weight' => $set->weight,
                        'reps' => $set->reps
                    ]);
                    $exercise->total_weight += $set->total_weight;
                    $exercise->total_reps += $set->reps;
                }
            }
        }

        return [
            'status' => 'success',
            'data' => [
                'day' => $day,
                'exercises' => $exercises
            ]
        ];
    }

}
