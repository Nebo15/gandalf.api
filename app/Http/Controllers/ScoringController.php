<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:49
 */

namespace App\Http\Controllers;

use App\Services\Scoring;
use App\Models\ScoringHistory;
use Illuminate\Http\Request;
use App\Http\Services\Response;

class ScoringController extends Controller
{
    public function history(Response $response)
    {
        return $response->json(ScoringHistory::paginate()->toArray());
    }

    public function check(Request $request, Response $response, Scoring $scoring)
    {
        return $response->json($scoring->check($request->all()));
    }
}
