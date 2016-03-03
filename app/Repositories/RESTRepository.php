<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:58
 */

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class RESTRepository
{
    protected $modelClassName;

    protected function __construct()
    {
        if (!$this->modelClassName) {
            throw new \Exception("You should set \$modelClassName in " . get_called_class());
        }
        if (!($this->modelClassName instanceof Model)) {
            throw new \Exception(
                "Model $this->modelClassName should be instance of Illuminate\\Database\\Eloquent\\Model"
            );
        }
    }

    /**
     * @param $id
     * @return Model
     */
    public function read($id)
    {
        return call_user_func_array([$this->modelClassName, 'findById'], [$id]);
    }

    /**
     * @param null $size
     * @return \Illuminate\Pagination\LengthAwarePaginator
     * @throws \Exception
     */
    public function readList($size = null)
    {
        return call_user_func_array([$this->modelClassName, 'paginate'], [intval($size)]);
    }

    public function createOrUpdate($values, $id = null)
    {
        $model = $id ? $this->read($id) : new $this->modelClassName;
        $model->fill($values)->save();

        return $model;
    }

    public function copy($id)
    {
        $values = $this->read($id)->getAttributes();
        unset($values[call_user_func([$this->modelClassName, 'getKeyName'])]);

        return $this->createOrUpdate($values);
    }

    public function delete($id)
    {
        return $this->read($id)->delete();
    }
}