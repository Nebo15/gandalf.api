<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Http\Controllers;

use App\Models\Table;
use App\Services\ConditionsTypes;
use Nebo15\LumenApplicationable\Models\Application;

class ProjectsController extends AbstractController
{
    protected $repositoryClassName = '';

    protected $validationRules = [
        'import' => [
            'file' => 'required|mimes:tar.gz'
        ]
    ];

    public function deleteProject(Application $application)
    {
        $current_application = app()->offsetGet('applicationable.application');
        Table::where(['applications' => ['$in' => [$application->_id]]])->delete();
        $current_application->delete();

        return $this->response->json();
    }

    public function export(Application $application)
    {
        $appId = $application->_id;
        $prefixTmpFile = sys_get_temp_dir() . strval(new \MongoId) . '/';
        $collections = [
            'tables' => "'{applications: \"{$appId}\"}'",
            'decisions' => "'{applications: \"{$appId}\"}'",
            'changelogs' => "'{\"model.attributes.applications\": \"{$appId}\"}'",
            'applications' => "'{_id: \"{$appId}\"}'",
        ];
        foreach ($collections as $collection => $query) {
            exec(sprintf(
                "mongoexport -h %s --port %s -d %s -q %s -c %s --out %s",
                env('DB_HOST'),
                env('DB_PORT'),
                env('DB_DATABASE'),
                $query,
                $collection,
                $prefixTmpFile . $collection . '.json'
            ));
        }
        # create archive
        $archiveName = $prefixTmpFile . "dump-" . date('Y-m-d_H:i:s') . ".tar.gz";
        exec(sprintf("cd %s && tar -cvzf %s *.json", $prefixTmpFile, $archiveName));

        return response()->download($archiveName);
    }

    public function import(ConditionsTypes $conditionsTypes)
    {
        $this->validateRoute();
        $tableRules = $this->getTableRules($conditionsTypes);

        return $this->response->json();
    }
}
