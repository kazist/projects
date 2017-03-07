<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of TasksController
 *
 * @author sbc
 */

namespace Projects\Tasks\Code\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;

class TasksController extends BaseController {

    public function indexAction($offset = 0, $limit = 10) {

        $document = $this->container->get('document');
        $user = $document->user;

        if (!$user->is_admin) {
            $this->model->project_ids = $this->model->getUserProjects($user->id);
        }

        return parent::indexAction($offset, $limit);
    }

}
