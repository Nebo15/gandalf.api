<?php
/*
 * This code was generated automatically by Nebo15/REST
 */

namespace App\Models;

use Nebo15\REST\Traits\ListableTrait;
use Nebo15\REST\Interfaces\ListableInterface;

/**
 * Class Tree
 * @package App\Models
 * @property string $title
 * @property string $description
 * @property string $table_id
 * @property array $transitions
 */
class Tree extends Base implements ListableInterface
{
    use ListableTrait;

    protected $fillable = ['title', 'description', 'table_id', 'transitions'];

    protected $listable = ['_id', 'title', 'description', 'table_id'];

    protected $visible = ['_id', 'title', 'description', 'table_id', 'transitions'];

    protected $attributes = [
        'title' => '',
        'description' => '',
        'transitions' => [],
    ];

    /**
     * @param $decision
     * @return Tree
     */
    public function getNextNode($decision)
    {
        $key = strtolower($decision);
        if (array_key_exists($key, $this->transitions)) {
            return new Tree($this->transitions[$key]);
        }

        return false;
    }
}
