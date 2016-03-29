<?php
/**
 * Author: Paul Bardack paul.bardack@gmail.com http://paulbardack.com
 * Date: 29.03.16
 * Time: 16:10
 */

namespace App\Services;

use App\Models\Decision;
use App\Repositories\GroupsRepository;
use App\Repositories\TablesRepository;
use App\Repositories\TreesRepository;

class TreeDecisionsMaker extends DecisionsMaker
{
    protected $treesRepository;

    public function __construct(
        TreesRepository $treesRepository,
        TablesRepository $tablesRepository,
        GroupsRepository $groupsRepository
    ) {
        $this->treesRepository = $treesRepository;

        parent::__construct($tablesRepository, $groupsRepository);
    }

    public function make($treeId, $values)
    {
        $tree = $this->treesRepository->read($treeId);

        $validated = false;
        $decisions = [];
        do {
            $table = $this->tablesRepository->read($tree->table_id);
            if (!$validated) {
                $this->validateFields($table, $values);
                $validated = true;
            }

            $decisionData = array_merge(
                $this->prepareDecisionData($table, $values, null, $tree),
                $this->makeDecision($table, $values)
            );

            $finalDecision = $decisionData['final_decision'];
            $decisions[] = Decision::create($decisionData)->toConsumerArray();

        } while ($tree = $tree->getNextNode($finalDecision));


        if ($decisionData['webhook']) {
            # create webhook service
        }

        return [
            'final_decision' => $finalDecision,
            'decisions' => $decisions,
        ];
    }
}
