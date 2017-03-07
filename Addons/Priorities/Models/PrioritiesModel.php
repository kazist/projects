<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Priorities\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;
use Kazist\Model\BaseModel;

class PrioritiesModel extends BaseModel {

    public $block_id = '';

    public function getInfo() {
        return 'Hello World!';
    }

    public function getPriorities() {

        $factory = new KazistFactory;

        $session = $this->container->get('session');
        $current_project = $session->get('current_project');

        $project_filter = ($current_project) ? 'project_id=' . (int) $current_project : '1=1';

        $query = $factory->getQueryBuilder('#__projects_priorities', 'pp');
        $query->addSelect('(SELECT COUNT(*) FROM #__projects_tasks WHERE priority_id = pp.id  AND ' . $project_filter . ' AND (closed=0 OR closed IS NULL)) AS total_tasks');
        $records = $query->loadObjectList();

        return $records;
    }

    public function getPrioritiesCounter($priorities) {

        $tmp_array = array();

        if (!empty($priorities)) {
            foreach ($priorities as $priority) {

                $group_name = substr($priority->title, 0, 10);
                $tmp_array[] = '["' . $group_name . '(' . $priority->total_tasks . ')",' . urlencode($priority->total_tasks) . ']';
            }
        }

        $tmp_array = '[' . implode(',', $tmp_array) . ']';

        return $tmp_array;
    }

}
