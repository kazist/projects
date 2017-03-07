<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Calendar\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;
use Kazist\Model\BaseModel;

class CalendarModel extends BaseModel {

    public $block_id = '';

    public function getInfo() {
        return 'Hello World!';
    }

    public function getTasks() {

        $factory = new KazistFactory;

        $session = $this->container->get('session');
        $current_project = $session->get('current_project');

        $project_filter = ($current_project) ? 'project_id=' . (int) $current_project : '1=1';

        $query = $factory->getQueryBuilder('#__projects_tasks', 'pt');
        $query->addSelect('ps.color');
        $query->where('pt.closed=0 OR pt.closed IS NULL');
        $query->andWhere($project_filter);
        $records = $query->loadObjectList();

        return $records;
    }

    public function getTasksCounter($tasks) {
        $factory = new KazistFactory;
        $tmp_array = array();

        if (!empty($tasks)) {
            foreach ($tasks as $task) {

                $start_time = (strtotime($task->start_time) <> '') ? $task->start_time : date('Y-m-d H:i:s');
                $end_time = (strtotime($task->end_time) <> '') ? $task->end_time : date('Y-m-d H:i:s');

                $description = '<b>Title</b> :- ' . $task->title . '<br> '
                        . '<b>Project</b> :- ' . $task->project_id_title . '<br>'
                        . '<b>Start</b> :- ' . $start_time . '<br>'
                        . '<b>End</b> :- ' . $end_time . '<br>'
                        . '';

                $tmp_array[] = '{ '
                        . 'title: \'' . $task->id . '\','
                        . 'start: ' . strtotime($start_time) . ','
                        . 'end: ' . strtotime($end_time) . ','
                        . 'allDay: false,'
                        . 'url: \'' . $factory->generateUrl('projects.tasks', array('id' => $task->id), 0) . '\','
                        . 'description: \'<b>' . $description . '\','
                        . 'backgroundColor: "#' . $task->color . '",'
                        . 'borderColor: "#' . $task->color . '" '
                        . '}';
            }
        }

        $tmp_array = '[' . implode(',', $tmp_array) . ']';

        return $tmp_array;
    }

}
