<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:49
 */

namespace App\Http\Controllers;

use App\Services\Scoring;
use Illuminate\Http\Request;
use App\Http\Services\Response;
use App\Models\DecisionHistory;
use App\Repositories\DecisionRepository;
use App\Http\Traits\ValidatesRequestsCatcher;

class ConsumerController extends Controller
{
    use ValidatesRequestsCatcher;

    private $response;
    private $decisionRepository;

    public function __construct(Response $response, DecisionRepository $decision)
    {
        $this->response = $response;
        $this->decisionRepository = $decision;
    }

    public function check(Request $request, Scoring $scoring, $id)
    {
        return $this->response->json($scoring->check($id, $request->all()));
    }

    public function decisions(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->consumerHistory($request->input('size')),
            [],
            function (DecisionHistory $decisionHistory) {
                return $decisionHistory->toConsumerArray();
            }
        );
    }

    public function decision($id)
    {
        return $this->response->json($this->decisionRepository->consumerHistoryItem($id));
    }
}
