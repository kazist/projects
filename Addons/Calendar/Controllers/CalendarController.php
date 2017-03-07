<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Calendar\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\Calendar\Models\CalendarModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class CalendarController extends AddonController {

    public $flexview_id = '';

    function indexAction() {

        $model = new CalendarModel;
        $tasks = $model->getTasks();
        $tasks_json = $model->getTasksCounter($tasks);

        $data_arr['tasks'] = $tasks;
        $data_arr['data'] = $tasks_json;
        $data_arr['chart_height'] = 250;

        $this->html .= $this->render('Projects:Addons:Calendar:views:calendar.twig', $data_arr);
        $this->html .= $this->render('Kazist:views:calendar:calendar.index.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
