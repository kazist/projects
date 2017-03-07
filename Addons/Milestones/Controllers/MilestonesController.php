<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Milestones\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\Milestones\Models\MilestonesModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class MilestonesController extends AddonController {

    public $flexview_id = '';

    function indexAction() {

        $model = new MilestonesModel;

        $milestones = $model->getMilestones();
        $milestones_json = $model->getMilestonesCounter($milestones);

        $data_arr['milestones'] = $milestones;
        $data_arr['data'] = $milestones_json;
        $data_arr['chart_height'] = 250;

        $this->html .= $this->render('Kazist:views:chart:chart.show.pie.twig', $data_arr);
        //  $this->html .= $this->render('Projects:Addons:Types:views:types.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
