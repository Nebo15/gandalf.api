<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:37
 */

namespace App\Http\Controllers;

use App\Models\RESTListable;
use Illuminate\Http\Request;
use App\Http\Services\Response;
use App\Repositories\RESTRepository;
use App\Http\Traits\ValidatesRequestsCatcher;

abstract class RESTController extends \Laravel\Lumen\Routing\Controller
{
    use ValidatesRequestsCatcher;

    private $request;
    private $response;
    private $repository;

    protected $repositoryClassName;

    protected $validationRules = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    protected function getRepository()
    {
        if (!$this->repository) {
            if (!$this->repositoryClassName) {
                throw new \Exception("You should set \$repositoryClassName");
            }
            $this->repository = new $this->repositoryClassName;
            if (!($this->repository instanceof RESTRepository)) {
                throw new \Exception("Repository $this->repositoryClassName should be instance of RESTRepository");
            }
        }

        return $this->repository;
    }

    public function create()
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($this->request->all())->toArray(),
            Response::HTTP_CREATED
        );
    }

    public function copy($id)
    {
        return $this->response->json(
            $this->getRepository()->copy($id)->toArray()
        );
    }

    public function read($id)
    {
        return $this->response->json($this->getRepository()->read($id)->toArray());
    }

    public function readList(Request $request)
    {
        return $this->response->jsonPaginator(
            $this->getRepository()->readList($request->input('size')),
            [],
            function (RESTListable $model) {
                return $model->toListArray();
            }
        );
    }

    public function update(Request $request, $id)
    {
        $this->validateRoute();

        return $this->response->json(
            $this->getRepository()->createOrUpdate($request->request->all(), $id)->toArray()
        );
    }

    public function delete($id)
    {
        return $this->response->json(
            $this->getRepository()->delete($id)
        );
    }

    public function validateRoute()
    {
        $action = debug_backtrace()[1]['function'];
        if (isset($this->validationRules[$action])) {
            $this->validate($this->request, $this->validationRules[$action]);
        }
    }
}
