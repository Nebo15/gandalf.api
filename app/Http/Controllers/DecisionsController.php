<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DecisionTable;
use App\Http\Services\Response;
use App\Repositories\DecisionRepository;
use App\Http\Traits\ValidatesRequestsCatcher;

class DecisionsController extends Controller
{
    use ValidatesRequestsCatcher;

    private $decisionRepository;
    private $response;

    public function __construct(Response $response, DecisionRepository $decision)
    {
        $this->decisionRepository = $decision;
        $this->response = $response;
    }

    public function index(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->all($request->input('size')),
            [],
            function (DecisionTable $decisionTable) {
                return $decisionTable->toListArray();
            }
        );
    }

    public function get($id)
    {
        return $this->response->json($this->decisionRepository->get($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, ['table' => 'required|decisionStruct']);

        return $this->response->json(
            $this->decisionRepository->create($request->input('table')),
            Response::HTTP_CREATED
        );
    }

    public function cloneModel($id)
    {
        return $this->response->json(
            $this->decisionRepository->cloneModel($id)
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['table' => 'required|decisionStruct']);

        return $this->response->json(
            $this->decisionRepository->update($id, $request->input('table'))
        );
    }

    public function delete($id)
    {
        return $this->response->json(
            $this->decisionRepository->delete($id)
        );
    }

    public function history(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->history($request->input('size'), $request->input('table_id'))
        );
    }

    public function historyItem($id)
    {
        return $this->response->json($this->decisionRepository->historyItem($id));
    }
}
