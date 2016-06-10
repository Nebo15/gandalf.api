<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use App\Models\Table;
use App\Services\DbTransfer;
use Nebo15\REST\AbstractController;
use Nebo15\LumenApplicationable\Models\Application;

class ProjectsController extends AbstractController
{
    protected $repositoryClassName = '';

    protected $validationRules = [];

    public function deleteProject(Application $application)
    {
        $current_application = app()->offsetGet('applicationable.application');
        Table::where(['applications' => ['$in' => [$application->_id]]])->delete();
        $current_application->delete();

        return $this->response->json();
    }

    public function export(DbTransfer $dbTransfer, Application $application)
    {
        return response()->download($dbTransfer->export($application->_id));
    }

    public function import(DbTransfer $dbTransfer, Application $application)
    {
        $this->validateRoute();

        $fileName = "dump-" . date('Y-m-d_H:i:s') . ".tar.gz";
        $prefixTmpFile = sys_get_temp_dir() . strval(new \MongoId);

        $this->request->file('file')->move($prefixTmpFile, $fileName);
        $dbTransfer->prepareImport($this->request->user(), $application->_id, $prefixTmpFile, $fileName);

        return $this->response->json([], 202);
    }
}
