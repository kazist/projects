<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\ProjectSummary\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\ProjectSummary\Models\ProjectSummaryModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class ProjectSummaryController extends AddonController {

    public $flexview_id = '';

    function indexAction() {


        $this->model = new ProjectSummaryModel;

        $projectsummary = $this->model->getProjectSummary();

        $data_arr['projectsummary'] = $projectsummary;

        $this->html = $this->render('Projects:Addons:ProjectSummary:views:projectsummary.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
