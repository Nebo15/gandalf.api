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
        $tables = $this->groupsRepository->read($groupId)->tables;

        return strval($tables[rand(0, count($tables) - 1)]['_id']);
    }
}
