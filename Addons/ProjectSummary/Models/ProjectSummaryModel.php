<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\ProjectSummary\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\KazistFactory;
use Kazist\Service\Database\Query;
use Kazist\Model\BaseModel;

class ProjectSummaryModel extends BaseModel {

    public $block_id = '';

    public function getInfo() {
        return 'Hello World!';
    }

    public function getProjectSummary() {

        $factory = new KazistFactory;

        $session = $this->container->get('session');
        $current_project = $session->get('current_project');

        $project_filter = ($current_project) ? 'pp.id=' . (int) $current_project : '1=-1';

        $query = $factory->getQueryBuilder('#__projects_projects', 'pp');
        $query->where($project_filter);

        $record = $query->loadObject();

        return $record;
    }

}
