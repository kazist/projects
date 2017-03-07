<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Taskchart\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\Taskchart\Models\TaskchartModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class TaskchartController extends AddonController {

    public $flexview_id = '';

    function indexAction() {

        $limit = 12;

        $model = new TaskchartModel;

        $montharr = $model->getMonthArr($limit);
        $new_tasks = $model->getNewTasks($limit, $montharr, 'date_created');
        $closed_tasks = $model->getNewTasks($limit, $montharr, 'closed_date');

        $data_arr['new_tasks'] = implode(',', $new_tasks);
        $data_arr['closed_tasks'] = implode(',', $closed_tasks);
        $data_arr['montharr'] = '\'' . implode("', '", array_keys($new_tasks)) . '\'';

        $this->html .= $this->render('Projects:Addons:Taskchart:views:taskchart.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
