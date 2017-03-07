<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Taskchart\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;
use Kazist\Model\BaseModel;

class TaskchartModel extends BaseModel {

    public $block_id = '';

    public function getInfo() {
        return 'Hello World!';
    }

    public function getNewTasks($limit, $montharr, $field) {

        $session = $this->container->get('session');
        $current_project = $session->get('current_project');

        $one_year_ago = date('Y-m-01', strtotime("-" . $limit . " month"));
        $project_filter = ($current_project) ? 'pt.project_id=' . (int) $current_project : '1=1';

        $query = new Query();
        $query->select('YEAR(' . $field . ' ) AS year, MONTH( ' . $field . ' ) AS month, COUNT(*) AS total');
        $query->from('#__projects_tasks', 'pt');
        $query->andWhere($project_filter);
        $query->andWhere('pt.' . $field . '  >:one_year_ago');
        $query->orderBy('MONTH( ' . $field . '  )');
        $query->addOrderBy('YEAR( ' . $field . '  )');
        $query->setParameter('one_year_ago', $one_year_ago);

        $records = $query->loadObjectList();

        return $this->reformatData($records, $montharr);
    }

    public function reformatData($records, $montharr) {

        foreach ($records as $key => $record) {
            if ($record->year != '' && $record->year != 0) {
                $montharr[$record->year . ':' . $record->month] = $record->total;
            }
        }

        return $montharr;
    }

    public function getMonthArr($limit) {

        $month_arr = array();

        for ($i = 0; $i <= $limit; $i++) {

            $month = date('m', strtotime("-$i month"));
            $year = date('Y', strtotime("-$i month"));

            $month_arr[$year . ':' . $month] = 0;
        }

        return array_reverse($month_arr);
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
