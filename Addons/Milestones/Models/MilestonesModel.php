<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Milestones\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;
use Kazist\Model\BaseModel;

class MilestonesModel extends BaseModel {

    public $block_id = '';

    public function getInfo() {
        return 'Hello World!';
    }

    public function getMilestones() {

        $factory = new KazistFactory;

        $session = $this->container->get('session');
        $current_project = $session->get('current_project');

        $project_filter = ($current_project) ? 'project_id=' . (int) $current_project : '1=1';

        $query = $factory->getQueryBuilder('#__projects_milestones', 'pm');
        $query->addSelect('(SELECT COUNT(*) FROM #__projects_tasks WHERE milestone_id = pm.id AND ' . $project_filter . ' AND (closed=0 OR closed IS NULL)) AS total_tasks');
        $records = $query->loadObjectList();

        return $records;
    }

    public function getMilestonesCounter($milestones) {

        $tmp_array = array();

        if (!empty($milestones)) {
            foreach ($milestones as $milestone) {

                $group_name = substr($milestone->title, 0, 8);
                $tmp_array[] = '["' . strtolower($group_name) . '(' . $milestone->total_tasks . ')",' . urlencode($milestone->total_tasks) . ']';
            }
        }

        $tmp_array = '[' . implode(',', $tmp_array) . ']';

        return $tmp_array;
    }

}
