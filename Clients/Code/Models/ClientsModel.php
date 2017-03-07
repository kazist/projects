<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Projects\Clients\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Json\Json;
use Joomla\Uri\Uri;
use Kazist\Service\Media\MediaManager;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class ClientsModel extends BaseModel {

    public function saveClientDetail($client_id, $form) {

        $new_members = $form['members']['new'];
        $existing_members = $form['members']['exist'];

        $this->saveMembers($client_id, $existing_members, 'exist');
        $this->saveMembers($client_id, $new_members, 'new');
    }

    public function saveMembers($client_id, $members, $type) {

        $existing_arr = array();
        $factory = new KazistFactory();

        if (!empty($members)) {

            foreach ($members as $key => $member) {

                $data_obj = new \stdClass();
                $data_obj->user_id = $member;
                $data_obj->client_id = $client_id;

                $existing_arr[] = $factory->saveRecord('#__projects_clients_members', $data_obj, array('user_id=:user_id', 'client_id=:client_id'), $data_obj);
            }

            if ($type == 'exist') {
                $this->deleteRemovedMembers($client_id, $existing_arr);
            }
        }
    }

    public function deleteRemovedMembers($client_id, $existing_arr) {

        $existing_arr = array_unique($existing_arr);

        $query = new Query();
        $query->delete('#__projects_clients_members');
        $query->where('client_id=:client_id');
        $query->setParameter('client_id', $client_id);
        if (!empty($existing_arr)) {
            $query->andWhere('id NOT IN  (' . implode(',', $existing_arr) . ')');
        }

        $query->execute();
        //  print_r((string)$query); exit;
    }

    //xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

    public function getMemberAutoCompleteSetting() {

        $autocomplete = array();

        $autocomplete["parameters"]["general"] = array();
        $autocomplete["parameters"]["save"] = array("index" => "");
        $autocomplete["parameters"]["media"] = array();
        $autocomplete["parameters"]["payment"] = array();

        $autocomplete["parameters"]["source"] = array(
            "data_source" => "db_table",
            "join_field" => "id",
            "display_field" => array('name'),
            "join_table_name" => "users_users",
        );

        return $autocomplete;
    }

    public function getClientMembersList($client_id) {

        $tmp_array = array();
        $members = $this->getClientMembers($client_id);

        foreach ($members AS $key => $member) {

            $query = $this->getQueryBuilder('projects_clients', 'pc');

            $query->where('pc.id=' . (int) $member->member_id);

            $records = $query->loadObject();

            $tmp_array[] = $records;
        }

        return $tmp_array;
    }

    public function getMember($member_id) {

        $query = $this->getQueryBuilder('projects_clients', 'pc');

        $query->where('pc.id=' . (int) $member_id);

        $record = $query->loadObject();

        return $record;
    }

    public function getClientMembers($client_id) {

        $query = $this->getQueryBuilder('projects_clients_members', 'pcm');

        $query->where('pcm.client_id=' . (int) $client_id);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getCategoryMembers($category_id, $offset = 0, $limit = 6) {

        $json = new Json();
        $factory = new KazistFactory();
        $db = $factory->getDatabase();
        $user = $factory->getUser();

        $json_object = $json->getJson('member', 'member', 'member');
        $table_alias = $json_object->table_alias;

        $query = $db->getQuery(true);
        $query = $this->getSqlQuery($json_object, $query, $is_view);

        if ($category_id) {
            $query->where($table_alias . '.category_id=' . $category_id);
        } else {
            $query->where('1=-1');
        }

        $query->order($table_alias . '.id DESC');

        $db->setQuery($query, $offset, $limit);
        $records = $db->loadObjectList();

        return $records;
    }

}
