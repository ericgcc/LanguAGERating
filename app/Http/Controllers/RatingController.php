<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rating;
use Illuminate\Support\Facades\DB;

class RatingController extends Controller
{
    public function store(Request $request){
        $ratings =  $request->input("ratings");
        $number_of_questions =  $request->input("number_of_questions");
        $number_of_tracks =  $request->input("number_of_tracks");

        for($t = 0; $t < $number_of_tracks; $t++)
            for ($q = 0; $q < $number_of_questions; $q++)
                try {
                    DB::insert(DB::raw("INSERT INTO ratings values(:user_id, :question_id, :track_id, :rating)
                                  ON DUPLICATE KEY UPDATE rating = :new_rating"),
                        ['user_id' => Auth::id(), 'question_id' => $q + 1, 'track_id' => $t + 1,
                            'rating' => $ratings[$t][$q] == "" ? NULL : floatval($ratings[$t][$q]),
                            'new_rating' => $ratings[$t][$q] == "" ? NULL : floatval($ratings[$t][$q])]);
                }catch(\Exception $e){
                    return response()->json("The data could not be saved, try again.");
                }
        return response()->json("true");
    }
}
