<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Response;
use App\Repositories\DecisionRepository;

class DecisionsController extends Controller
{
    private $decisionRepository;
    private $response;

    public function __construct(Response $response, DecisionRepository $decision)
    {
        $this->decisionRepository = $decision;
        $this->response = $response;
    }

    public function index(Request $request)
    {
        return $this->response->json($this->decisionRepository->all($request->get('size')));
    }

    public function get($id)
    {
        return $this->response->json($this->decisionRepository->get($id));
    }

    public function create(Request $request)
    {
        $this->validate($request, ['decision' => 'required|decisionStruct']);

        return $this->response->json(
            $this->decisionRepository->create($request->get('decision'))
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, ['decision' => 'required|decisionStruct']);

        return $this->response->json(
            $this->decisionRepository->update($id, $request->get('decision'))
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
        return $this->response->json($this->decisionRepository->history($request->get('size')));
    }

    public function historyItem($id)
    {
        return $this->response->json($this->decisionRepository->historyItem($id));
    }
}
