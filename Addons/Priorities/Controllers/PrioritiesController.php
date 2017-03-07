<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Priorities\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\Priorities\Models\PrioritiesModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class PrioritiesController extends AddonController {

    public $flexview_id = '';

    function indexAction() {

        $model = new PrioritiesModel;

        $priorities = $model->getPriorities();
        $priorities_json = $model->getPrioritiesCounter($priorities);

        $data_arr['priorities'] = $priorities;
        $data_arr['data'] = $priorities_json;

        $this->html .= $this->render('Kazist:views:chart:chart.show.pie.twig', $data_arr);
        //  $this->html .= $this->render('Projects:Addons:Types:views:types.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
