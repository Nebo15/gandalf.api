<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Table;
use Nebo15\LumenApplicationable\ApplicationableHelper;
use Nebo15\REST\AbstractController;

class ProjectsController extends AbstractController
{
    protected $repositoryClassName = '';

    protected $validationRules = [];

    public function deleteProject()
    {
        $current_application = app()->offsetGet('applicationable.application');
        Table::where(['applications' => ['$in' => [ApplicationableHelper::getApplicationId()]]])->delete();
        Group::where(['applications' => ['$in' => [ApplicationableHelper::getApplicationId()]]])->delete();
        $current_application->delete();

        return $this->response->json();
    }
}
