<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Response;
use App\Repositories\DecisionRepository;

class DecisionsController extends Controller
{
    public function index(Response $response, DecisionRepository $decision)
    {
        return $response->json($decision->all());
    }

    public function set(Request $request, Response $response, DecisionRepository $decision)
    {
        $this->validate($request, [
            'decision' => 'required|decisionStruct',
        ]);

        return $response->json($decision->update($request->get('decision')));
    }
}
