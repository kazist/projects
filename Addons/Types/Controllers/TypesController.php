<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Addons\Types\Controllers;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Controller\AddonController;
use Projects\Addons\Types\Models\TypesModel;

/**
 * Kazist view class for the application
 *
 * @since  1.0
 */
class TypesController extends AddonController {

    public $flexview_id = '';

    function indexAction() {



        $model = new TypesModel;

        $types = $model->getTypes();
        $types_json = $model->getTypesCounter($types);


        $data_arr['types'] = $types;
        $data_arr['data'] = $types_json;

        $this->html .= $this->render('Kazist:views:chart:chart.show.pie.twig', $data_arr);
        //  $this->html .= $this->render('Projects:Addons:Types:views:types.twig', $data_arr);

        $response = $this->response($this->html);

        return $response;
    }

}
