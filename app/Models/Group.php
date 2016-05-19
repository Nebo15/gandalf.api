<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Models;

use App\Repositories\TablesRepository;
use Nebo15\REST\Traits\ListableTrait;
use Nebo15\REST\Interfaces\ListableInterface;

/**
 * Class Group
 * @package App\Models
 * @property array $tables;
 * @property string $title;
 * @property string $description;
 * @property string $probability;
 */
class Group extends Base implements ListableInterface
{
    use ListableTrait;

    protected $fillable = ['tables', 'title', 'description', 'probability'];

    protected $listable = ['_id', 'title', 'description', 'probability', 'tables'];

    protected $visible = ['_id', 'title', 'description', 'probability', 'tables'];

    protected $attributes = [
        'title' => '',
        'description' => ''
    ];

    protected $casts = [
        '_id' => 'string',
    ];

    public function toArray()
    {
        $data = parent::toArray();
        $data['tables'] = $this->getTablesAttribute($data['tables']);

        return $data;
    }

    public function setTablesAttribute($tables)
    {
        for ($i = 0; $i < count($tables); $i++) {
            if (isset($tables[$i]['_id']) and !($tables[$i]['_id'] instanceof \MongoId)) {
                $tables[$i]['_id'] = new \MongoId($tables[$i]['_id']);
            }
        }
        $this->attributes['tables'] = $tables;
    }

    public function getTablesAttribute($tables)
    {
        if ($tables) {
            $ids = [];
            foreach ($tables as $table) {
                $ids[] = strval($table['_id']);
            }
            $tables = [];
            $tableModels = (new TablesRepository)->findByIds($ids);
            foreach ($tableModels as $tableModel) {
                $tables[] = [
                    '_id' => $tableModel->getId(),
                    'title' => $tableModel->title,
                    'description' => $tableModel->description,
                ];
            }
            return $tables;
        }

        return [];
    }
}
