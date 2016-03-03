<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:37
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\Response;
use App\Http\Traits\ValidatesRequestsCatcher;

abstract class RESTController extends \Laravel\Lumen\Routing\Controller
{
    use ValidatesRequestsCatcher;

    private $response;
    protected $repository;

    public function __construct(Response $response)
    {
        $this->response = $response;
    }

    public function getRepository()
    {
        if (!$this->repository) {

        }

        return $this->repository;
    }

    public function create(Request $request)
    {
        $this->validate($request, $this->tableValidationRules);

        return $this->response->json(
            $this->repository->create($request->input('table'))->toArray(),
            Response::HTTP_CREATED
        );
    }

    public function cloneModel($id)
    {
        return $this->response->json(
            $this->decisionRepository->cloneModel($id)->toArray()
        );
    }

    public function read($id)
    {
        return $this->response->json($this->decisionRepository->get($id)->toArray());
    }

    public function readList(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->decisionRepository->all($request->input('size')),
            [],
            function (DecisionTable $decisionTable) {
                return $decisionTable->toListArray();
            }
        );
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, $this->tableValidationRules);

        return $this->response->json(
            $this->decisionRepository->update($id, $request->input('table'))->toArray()
        );
    }

    public function delete($id)
    {
        return $this->response->json(
            $this->decisionRepository->delete($id)
        );
    }
}