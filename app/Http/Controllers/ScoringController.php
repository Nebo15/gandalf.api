<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScoringController extends Controller
{
    public function rules(Request $request)
    {
        $this->validate($request, [
            'conditions_types' => 'required|conditionsStruct',
            'rules' => 'required|array'
        ]);
    }

    public function check()
    {
        
    }
}
