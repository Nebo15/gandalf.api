<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 08.06.16
 * Time: 14:11
 */

namespace App\Services;

class DbTransfer
{
    public function export($appId)
    {
        # ToDo; check that collections not empty
        $prefixTmpFile = sys_get_temp_dir() . strval(new \MongoDB\BSON\ObjectId) . '/';
        $collections = [
            'tables' => "'{applications: \"{$appId}\"}'",
            'decisions' => "'{applications: \"{$appId}\"}'",
            'changelogs' => "'{\"model.attributes.applications\": \"{$appId}\"}'",
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

        return $archiveName;
    }
}
