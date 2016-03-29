<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 02.03.16
 * Time: 13:23
 */

namespace App\Services;

use App\Repositories\GroupsRepository;

class GroupsBalancer
{
    private $groupsRepository;

    private $lastGroup = null;

    public function __construct(GroupsRepository $groupsRepository)
    {
        $this->groupsRepository = $groupsRepository;
    }

    /**
     * @param $groupId
     * @return string MongoID
     */
    public function getTable($groupId)
    {
        $this->lastGroup = $this->groupsRepository->read($groupId);
        $tables = $this->lastGroup->tables;

        return strval($tables[rand(0, count($tables) - 1)]['_id']);
    }

    public function getLastGroup()
    {
        return $this->lastGroup;
    }
}
