<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 08.06.16
 * Time: 14:11
 */

namespace App\Services;

use Log;
use App\Models\User;
use App\Models\Table;
use Illuminate\Validation\Validator;

class DbTransfer
{
    private $user;
    private $appId;
    private $dirPath;
    private $fileName;
    private $errors = [];

    private $table;

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function export($appId)
    {
        # ToDo; check that collections not empty
        $prefixTmpFile = sys_get_temp_dir() . strval(new \MongoId) . '/';
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

    public function prepareImport(User $user, $appId, $dirPath, $fileName)
    {
        $this->user = $user;
        $this->appId = $appId;
        $this->dirPath = $dirPath;
        $this->fileName = $fileName;
    }

    public function import()
    {
        if ($this->appId and $this->dirPath and $this->fileName) {
            Log::debug('import started');
            $phar = new \PharData($this->dirPath . DIRECTORY_SEPARATOR . $this->fileName);
            $phar->decompress()->extractTo($this->dirPath);
            Log::debug('Archive decompressed');

            $jsonFileNames = ['tables.json', 'decisions.json', 'changelogs.json'];
            $passedJsonFiles = scandir($this->dirPath);
            Log::debug('Scan decompressed files');
            if (count(array_intersect($jsonFileNames, $passedJsonFiles)) != count($jsonFileNames)) {
                $this->addError('missed_json_files', array_diff($jsonFileNames, $passedJsonFiles));
                return $this->sendErrors();
            }

            Log::debug('Read decompressed files');
            $filteredJson = array_fill_keys($jsonFileNames, []);
            foreach ($jsonFileNames as $fileName) {
                Log::debug('Read file ' . $fileName);
                $handle = fopen($this->dirPath . DIRECTORY_SEPARATOR . $fileName, "r");
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        // process the line read.
                        $decoded = json_decode($line, true);
                        if (json_last_error() == JSON_ERROR_NONE) {
                            $filteredJson[$fileName][] = $this->filterJson($fileName, $decoded);
                        } else {
                            $this->addError($fileName, 'cannot_decode_json', json_last_error_msg());
                            break;
                        }

                    }
                    fclose($handle);
                } else {
                    $this->addError($fileName, 'cannot read file ');
                }
            }
            if ($this->errors) {
                return $this->sendErrors();
            }
            $res = [];
            foreach ($filteredJson as $fileName => $tableData) {
                file_put_contents($this->dirPath . DIRECTORY_SEPARATOR . $fileName, $tableData);
                $cmd = sprintf(
                    "mongorestore -h %s --port %s -d %s -c %s %s",
                    env('DB_HOST'),
                    env('DB_PORT'),
                    env('DB_DATABASE'),
                    str_replace('.json', '', $fileName),
                    $this->dirPath . DIRECTORY_SEPARATOR . $fileName
                );
                $result = exec($cmd);

                return $res[$fileName] = [$cmd => $result];
            }
            Log::debug(print_r($res, true));
            return $res;
        }

        return false;
    }

    private function filterJson($fileName, array $tableData)
    {
        $rules = $this->getValidationRules($fileName);
        /** @var Validator $validator */
        if ($rules) {
            $validator = \Validator::make($tableData, $rules);
            if ($validator->fails()) {
                $this->addError($fileName, 'validation', $validator->getMessageBag());
                return false;
            }
        }

        return $tableData;
    }

    private function getValidationRules($fileName)
    {
        return [];
    }

    private function addError($type, $err, $msg = null)
    {
        if (!array_key_exists($type, $this->errors)) {
            $this->errors[$type] = [];
        }
        $this->errors[$type][] = $msg ?: $err;
    }

    private function sendErrors()
    {
        return $this->errors;
    }
}
