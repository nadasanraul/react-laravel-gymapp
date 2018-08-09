<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Set;

class Workout extends Model
{
    private $sets;
    private $workout;

    public function __construct($day) {
        $this->day = $day;
        $this->workout = [
            'day' => $this->day,
            'exercises' => []
        ];
        $this->sets = Set::with(['exercise'])
                        ->where('user_id', auth()->user()->id)
                        ->where('for_day', $this->day)
                        ->orderBy('created_at', 'asc')
                        ->get()
                        ->toArray();
        $this->getExerciseNames();
    }

    private function getExerciseNames() {
        $exercises = array_unique(array_map(function($set) {
            return $set['exercise']['name'];
        }, $this->sets));

        foreach($exercises as $exerciseName) {
            $this->setExercisetoWorkout($exerciseName);
        }
    }

    private function setExercisetoWorkout($name) {
        $exercise = [
            'name' => $name,
            'sets' => [],
            'total_weight' => 0,
            'total_reps' => 0
        ];
        $setsOfExercise = array_filter($this->sets, function($set) use ($name) {
            if($set['exercise']['name'] === $name) {
                return $set;
            }
        });


        foreach($setsOfExercise as $set) {
            if($set['for_day'] === $this->day) {
                array_push($exercise['sets'], [
                    'id' => $set['id'],
                    'weight' => $set['weight'],
                    'reps' => $set['reps']
                ]);
                $exercise['total_weight'] += $set['total_weight'];
                $exercise['total_reps'] += $set['reps'];
            }
        }

        array_push($this->workout['exercises'], $exercise);
    }

    public function getWorkout() {
        return $this->workout;
    }

}
