<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of ProjectsController
 *
 * @author sbc
 */

namespace Projects\Projects\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Projects\Tasks\Code\Models\TasksModel;

class ProjectsController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $taskModel = new TasksModel();
        $document = $this->container->get('document');
        $session = $this->container->get('session');
        $session->remove('current_project');

        $user = $document->user;

        if (!$user->is_admin) {
            $this->model->project_ids = $taskModel->getUserProjects($user->id);
        }

        return parent::indexAction($offset, $limit);
    }

    public function sendDailyTaskSummaryAction() {
        $this->model->sendDailyTaskSummary();
    }

}
