<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 08.06.16
 * Time: 14:11
 */

namespace App\Services;

use Drunken\Task;
use Drunken\Manager as DrunkenManager;

class DbTransfer
{
    private $drunken;

    public function __construct(DrunkenManager $drunken)
    {
        $this->drunken = $drunken;
    }

    public function export($appId)
    {
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

    public function import($appId, $dirPath, $fileName)
    {
        return $this->drunken->addTask(
            new Task('ProjectImport', [
                'db_host' => env('DB_HOST'),
                'db_port' => env('DB_PORT'),
                'db_name' => env('DB_DATABASE'),
                'dir' => $dirPath,
                'file' => $fileName,
                'appId' => $appId,
            ])
        );
    }
}
