<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Projects\Projects\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Service\Email\Email;
use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Search\Indexes\Code\Classes\ContentIndexing;
use Kazist\Service\Database\Query;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class ProjectsModel extends BaseModel {

    public $project_ids = array();

    public function appendSearchQuery($query) {

        $document = $this->container->get('document');
        $search = $document->search;
        $user = $document->user;

        parent::appendSearchQuery($query);

        if (!$user->is_admin) {
            if (!empty($this->project_ids)) {
                $query->andWhere('pt.project_id IN (' . implode(', ', $this->project_ids) . ')');
            } else {
                $query->andWhere('1=-1');
            }
        }
    }

    //  public function getQueryBuilder($table_name, $table_alias, $where_arr = array(), $parmeter_arr = array(), $ordering_arr = array(), $offset = 0, $limit = 10) {
    public function save($form = '') {

        $project = '';

        $factory = new KazistFactory();
        $form = (!empty($form)) ? $form : $this->request->get('form');


        if ($form['duration_id'] <> '') {

            $where_arr = array('pd.id=:id');
            $parmeter_arr = array('id' => $form['duration_id']);

            $duration = $factory->getRecord('#__projects_durations', 'pd', $where_arr, $parmeter_arr);
            $period = '+ ' . $duration->duration . $duration->period; // substr($duration->period, 0, 1);

            $startstamp = strtotime($form['start_time']);
            $endstamp = strtotime($period, $startstamp);

            $date = date('Y-m-d H:i:s', $endstamp);

            $form['end_time'] = $date;
        }

        $id = parent::save($form);


        if ($id) {
            $project = parent::getRecord($id);

            $this->updateProjectCounter($project);
            $this->sendEmailToProjectMembers($form, $project, $id);
            $this->sendEmailToProjectLeader($form, $project, $id);
        }

        return $id;
    }

    public function updateProjectCounter($project) {

        $query = $this->getQueryBuilder('#__projects_projects_components', 'ppc', array('ppc.project_id=:project_id'), array('project_id' => $project->id));
        $query->select('COUNT(ppc.id) as total');
        $components_record = $query->loadObject();

        $total_components = $components_record->total;

        if ($project->id) {
            $data_obj = new \stdClass();
            $data_obj->id = $project->id;
            $data_obj->total_components = $total_components;
            $this->saveRecord('#__projects_projects', $data_obj);
        }
    }

    public function getRecord($id = '') {

        $session = $this->container->get('session');
        $session->set('current_project', $id);

        $query = $this->getQueryBuilder();

        $query->leftJoin('pm', '#__users_users', 'lead_uu', 'lead_uu.id=pm.user_id');
        $query->addSelect('lead_uu.name as leader_id_name');

        $record = parent::getRecord($id, $query);

        $record->assignments = $this->getAssignments($record);
        $record->clients = $this->getClients($record);
        $record->documents = $this->getDocuments($record);
        $record->tasks = $this->getTasks($record);

        return $record;
    }

    public function getRecords($offset = '', $limit = '') {

        $records = parent::getRecords($offset, $limit);

        foreach ($records as $key => $record) {
            $records[$key]->assignments = $this->getAssignments($record);
            $records[$key]->clients = $this->getClients($record);
        }

        return $records;
    }

    public function getUserId($user_id) {

        $where_arr = array();
        $where_arr[] = 'uu.id=:user_id';

        $parameter_arr = array('user_id' => $user_id);

        $query = parent::getQueryBuilder('#__users_users', 'uu', $where_arr, $parameter_arr);

        $record = $query->loadObject();

        return $record;
    }

    public function sendEmailToProjectMembers($form, $project, $id) {

        $email = new Email();

        if ($id) {
            $project = ($project != '') ? $project : parent::getRecord($id);

            $where_arr = array('project_id=:project_id', 'is_sent=0 OR is_sent IS NULL');
            $parameter_arr = array('project_id' => $id);
            $query = parent::getQueryBuilder('#__projects_projects_members', 'ppm', $where_arr, $parameter_arr);

            $members = $query->loadObjectList();

            foreach ($members as $member) {

                $user = $this->getUserId($member->user_id);

                $parameters = array();
                $parameters['user'] = $user;
                $parameters['project'] = $project;

                $member->is_sent = 1;
                $this->saveRecordByEntity('#__projects_projects_members', $member);

                $email->sendDefinedLayoutEmail('projects.projects.assignment', $user->email, $parameters);
            }
        }
    }

    public function sendEmailToProjectLeader($form, $project, $id) {

        $email = new Email();

        if ($id) {

            $user = $this->getUserId($form['leader_id']);
            $project = ($project != '') ? $project : parent::getRecord($id);

            $parameters = array();
            $parameters['user'] = $user;
            $parameters['project'] = $project;

            if ($form['leader_id'] || $form['leader_id'] <> $project->leader_id) {
                $email->sendDefinedLayoutEmail('projects.projects.leader', $user->email, $parameters);
            }
        }
    }

    public function getAssignments($record) {

        $where_arr = array();
        $where_arr[] = 'ppm.project_id=:project_id';

        $parameter_arr = array('project_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_projects_members', 'ppm', $where_arr, $parameter_arr);
        $query->leftJoin('ppm', '#__media_media', 'mm', 'uu.avatar = mm.id');
        $query->addSelect('mm.file as avatar_file');
        $query->addSelect('mm.title as avatar_title');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getClients($record) {

        $where_arr = array();
        $where_arr[] = 'pcm.client_id=:client_id';

        $parameter_arr = array('client_id' => $record->client_id);

        $query = parent::getQueryBuilder('#__projects_clients_members', 'pcm', $where_arr, $parameter_arr);
        $query->leftJoin('pcm', '#__media_media', 'mm', 'uu.avatar = mm.id');
        $query->addSelect('mm.file as avatar_file');
        $query->addSelect('mm.title as avatar_title');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getDocuments($record) {

        $where_arr = array();
        $where_arr[] = 'ppd.project_id=:project_id';

        $parameter_arr = array('project_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_projects_documents', 'ppd', $where_arr, $parameter_arr);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getTasks($record) {

        $where_arr = array();
        $where_arr[] = 'pt.project_id=:project_id';

        $parameter_arr = array('project_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks', 'pt', $where_arr, $parameter_arr);

        $query->orderBy('id', 'DESC');
        $query->setMaxResults(5);

        $records = $query->loadObjectList();

        return $records;
    }

    /* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx send Daily Summary xxxxxxxxxxxxxxxxxxxxxxxx */

    public function sendDailyTaskSummary() {

        $email = new Email();

        $query = parent::getQueryBuilder('#__projects_projects', 'pp', array('completed=0 OR completed IS NULL '));
        $projects = $query->loadObjectList();

        foreach ($projects as $project) {

            $tasks = $this->getProjectTasks($project->id);

            /* xxxxxxxxxxxxxxxxxxxxx Send to the Project Leader xxxxxxxxxxxxxxxxxxxx */
            $user = $this->getUserId($project->leader_id);
            $parameters = array('user' => $user, 'project' => $project, 'tasks' => $tasks);
            $email->sendDefinedLayoutEmail('projects.projects.tasks.daily_summary', $user->email, $parameters);

            /* xxxxxxxxxxxxxxxxxxxxx Send to the assigned Members xxxxxxxxxxxxxxxxxxxx */
            $members_query = parent::getQueryBuilder('#__projects_projects_members', 'ppm', array('ppm.project_id=' . $project->id));
            $members = $members_query->loadObjectList();

            foreach ($members as $member) {

                $user = $this->getUserId($member->user_id);
                $parameters = array('user' => $user, 'project' => $project, 'tasks' => $tasks);
                $email->sendDefinedLayoutEmail('projects.projects.tasks.daily_summary', $user->email, $parameters);
            }

            /* xxxxxxxxxxxxxxxxxxxxx Send to the Clients xxxxxxxxxxxxxxxxxxxx */
            $client_query = parent::getQueryBuilder('#__projects_clients_members', 'pcm', array('pcm.project_id=' . $project->id));
            $clients = $client_query->loadObjectList();

            foreach ($clients as $client) {

                $user = $this->getUserId($client->user_id);
                $parameters = array('user' => $user, 'project' => $project, 'tasks' => $tasks);
                $email->sendDefinedLayoutEmail('projects.projects.tasks.daily_summary', $user->email, $parameters);
            }
        }
    }

    public function getProjectTasks($project_id) {

        $date_status_changed = date('Y-m-d H:i:s', strtotime('-1day'));

        $where_arr = array();
        $where_arr[] = 'uu.project_id=:project_id';
        $where_arr[] = 'uu.date_status_changed<:date_status_changed';

        $parameter_arr = array('project_id' => $project_id, 'date_status_changed' => $date_status_changed);

        $query = parent::getQueryBuilder('#__projects_tasks', 'pt', $where_arr, $parameter_arr);

        $record = $query->loadObject();

        return $record;
    }

}
