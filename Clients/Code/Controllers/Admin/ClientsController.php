<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

/**
 * Description of ClientsController
 *
 * @author sbc
 */

namespace Projects\Clients\Code\Controllers\Admin;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\BaseController;
use Kazist\KazistFactory;

class ClientsController extends BaseController {

    public function saveAction() {

        $factory = new KazistFactory();

        $form = $this->request->get('form');

        $client_id = $this->model->save($form);

        if ($client_id) {

            $this->model->saveClientDetail($client_id, $form);

            $msg = $form['title'] . ' Video Save Successfully';
            $factory->enqueueMessage($msg, 'info');
            $redirect = $this->generateUrl('admin.projects.clients.edit', array('id' => $client_id));
        } else {
            $msg = 'Members Not added due to some errors;';
            $factory->enqueueMessage($msg, 'error');
            $redirect = $this->generateUrl('admin.projects.clients');
        }

        return $this->redirect($redirect);
    }

}
