<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 15.02.16
 * Time: 16:49
 */

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Services\Scoring;
use Nebo15\REST\Response;
use Illuminate\Http\Request;
use App\Services\GroupsBalancer;
use App\Repositories\DecisionsRepository;
use Nebo15\REST\Traits\ValidatesRequestsTrait;

class ConsumerController extends Controller
{
    use ValidatesRequestsTrait;

    private $response;
    private $decisionsRepository;

    public function __construct(Response $response, DecisionsRepository $decisionsRepository)
    {
        $this->response = $response;
        $this->decisionsRepository = $decisionsRepository;
    }

    public function tableCheck(Request $request, Scoring $scoring, $id)
    {
        return $this->response->json($scoring->check($id, $request->all()));
    }

    public function groupCheck(Request $request, GroupsBalancer $balancer, Scoring $scoring, $id)
    {
        return $this->response->json($scoring->check($balancer->getTable($id), $request->all(), $id));
    }

    public function decisions(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionsRepository->readList($request->input('size')),
            [],
            function (Decision $decision) {
                return $decision->toConsumerArray();
            }
        );
    }

    public function decision($id)
    {
        return $this->response->json($this->decisionsRepository->getConsumerDecision($id));
    }
}
