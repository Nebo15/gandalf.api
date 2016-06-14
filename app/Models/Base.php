<?php
namespace App\Models;

use App\Exceptions\FailedToSaveModel;
use App\Exceptions\IdNotFoundException;
use Jenssegers\Mongodb\Model as Eloquent;

/**
 * Class Base
 * @package App\Models
 * @property string $_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
abstract class Base extends Eloquent
{
    const PRIMARY_KEY = '_id';
    protected $connection = 'mongodb';
    protected $validation_rules = [];

    protected $casts = [
        '_id' => 'string',
    ];

    public function getId()
    {
        return $this->{self::PRIMARY_KEY};
    }

    public function isNew()
    {
        return empty($this->getId());
    }

    public function createId()
    {
        $this->{self::PRIMARY_KEY} = new \MongoId();
    }

    /**
     * @param array $options
     * @return $this
     */
    public function save(array $options = [])
    {
        if (parent::save($options)) {
            return $this;
        }

        throw new FailedToSaveModel;
    }

    public static function findById($id)
    {
        if (empty($id)) {
            throw new IdNotFoundException;
        }

        return self::where(self::PRIMARY_KEY, $id)->firstOrFail();
    }
}
