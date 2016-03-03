<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 03.03.16
 * Time: 18:03
 */

namespace App\Http\Traits;

/**
 * Class RESTListable
 * @package App\Http\Traits
 * @property array $listable
 */
trait RESTListable
{
    abstract public function toArray();

    public function toListArray()
    {
        if (property_exists($this, 'listable') and $this->listable) {
            if (!is_array($this->listable)) {
                throw new \Exception("Property \$listable shoul be an array in " . get_class($this));
            }
            return $this->listable;
        } else {
            return $this->toArray();
        }
    }
}
