<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Projects\Tasks\Code\Models;

defined('KAZIST') or exit('Not Kazist Framework');

use Kazist\Model\BaseModel;
use Kazist\KazistFactory;
use Kazist\Service\Email\Email;
use Kazist\Service\Database\Query;
use Search\Indexes\Code\Classes\ContentIndexing;

/**
 * Description of MarketplaceModel
 *
 * @author sbc
 */
class TasksModel extends BaseModel {

    public $project_ids = array();

    public function appendSearchQuery($query) {

        $document = $this->container->get('document');
        $search = $document->search;
        $user = $document->user;
        $id = $this->request->get('id');

        parent::appendSearchQuery($query);

        if (!$id) {
            if (isset($search['futuretask']) && $search['futuretask']) {
                $query->andWhere('pt.start_time > :start_time');
                $query->setParameter('start_time', date('Y-m-d H:i:s', strtotime('+1day')));
                $query->orderBy('pt.start_time', 'DESC');
            } elseif (strtotime($search['start_date']) <> '') {
                $query->setParameter('start_date', $search['start_date'] . ' 00:00:00');
                $query->setParameter('end_date', $search['start_date'] . ' 23:59:59');
                $query->andWhere('pt.start_time BETWEEN :start_date AND :end_date');
            } else {
                $query->setParameter('start_time', date('Y-m-d H:i:s', strtotime('+1day')));
                $query->andWhere('pt.start_time < :start_time');
            }
        }

        if (!$user->is_admin) {
            if (!empty($this->project_ids)) {
                $query->andWhere('pt.project_id IN (' . implode(', ', $this->project_ids) . ')');
            } else {
                $query->andWhere('1=-1');
            }
        }

        return $query;
    }

    public function save($form = '') {

        $task = '';

        $factory = new KazistFactory();
        $form = (!empty($form)) ? $form : $this->request->get('form');

        $form = $this->getDefaultTask($form);

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
            $task = parent::getRecord($id);

            $this->updateProjectCounter($task);
            $this->updateTaskCounter($task);
            $this->sendEmailToTaskAssigned($form, $task, $id);
            $this->sendEmailOnStatusChange($form, $task, $id);
            $this->sendEmailOnTaskClosed($form, $task, $id);
        }

        return $id;
    }

    public function updateProjectCounter($task) {

        $query = $this->getQueryBuilder('#__projects_tasks', 'pt', array('pt.project_id=:project_id'), array('project_id' => $task->project_id));
        $query->select('COUNT(pt.id) as total');
        $total_record = $query->loadObject();

        $query->andWhere('pt.closed=1');
        $completed_record = $query->loadObject();

        $total_tasks = $total_record->total;
        $total_completed = $completed_record->total;

        if ($task->project_id) {
            $data_obj = new \stdClass();
            $data_obj->id = $task->project_id;
            $data_obj->total_tasks = $total_tasks;
            $data_obj->total_completed = $total_completed;
            $this->saveRecord('#__projects_projects', $data_obj);
        }
    }

    public function updateTaskCounter($task) {

        $query = $this->getQueryBuilder('#__projects_tasks_subtasks', 'pts', array('pts.task_id=:task_id'), array('task_id' => $task->id));
        $query->select('COUNT(pt.id) as total');
        $total_record = $query->loadObject();

        $query->andWhere('pts.closed=1');
        $completed_record = $query->loadObject();

        $total_subtasks = $total_record->total;
        $completed_subtasks = $completed_record->total;

        if ($task->id) {
            $data_obj = new \stdClass();
            $data_obj->id = $task->id;
            $data_obj->total_subtasks = $total_subtasks;
            $data_obj->completed_subtasks = $completed_subtasks;
            $this->saveRecord('#__projects_tasks', $data_obj);
        }
    }

    public function getDefaultTask($form) {

        $factory = new KazistFactory();
        $user = $factory->getUser();

        if (!$form['id']) {
            $form['status'] = ($form['status']) ? $form['status'] : $factory->getSetting('project_task_default_status');
            $form['milestone_id'] = ($form['milestone_id']) ? $form['milestone_id'] : $factory->getSetting('project_task_default_milestone_id');
            $form['priority_id'] = ($form['priority_id']) ? $form['priority_id'] : $factory->getSetting('project_task_default_priority_id');
            $form['type_id'] = ($form['type_id']) ? $form['type_id'] : $factory->getSetting('project_task_default_type_id');

            if (strtotime($form['start_time']) == '') {
                $form['start_time'] = date('Y-m-d H:i:s');
            }
        }


        if ($form['status']) {
            $form['date_status_changed'] = date('Y-m-d H:i:s');
            $form['status_changed_by'] = $user->id;
        }

        return $form;
    }

    public function getRecord($id = '') {

        $record = parent::getRecord($id);

        $session = $this->container->get('session');
        $session->set('current_project', $record->project_id);

        $record->assignments = $this->getAssignments($record);
        $record->documents = $this->getDocuments($record);
        $record->subtasks = $this->getSubtasks($record);
        $record->votes = $this->getVotes($record);
        $record->watchers = $this->getWatchers($record);

        return $record;
    }

    public function getRecords($offset = '', $limit = '') {

        $records = parent::getRecords($offset, $limit);

        foreach ($records as $key => $record) {
            $records[$key]->assignments = $this->getAssignments($record);
        }

        return $records;
    }

    public function sendEmailToTaskAssigned($form, $task, $id) {

        $email = new Email();

        if ($id) {
            $task = ($task != '') ? $task : parent::getRecord($id);

            $where_arr = array('task_id=:task_id', 'is_sent=0 OR is_sent IS NULL');
            $parameter_arr = array('task_id' => $id);

            $query = parent::getQueryBuilder('#__projects_tasks_assignments', 'pta', $where_arr, $parameter_arr);
            $members = $query->loadObjectList();

            foreach ($members as $member) {

                $user = $this->getUserId($member->user_id);

                $parameters = array();
                $parameters['user'] = $user;
                $parameters['task'] = $task;

                $member->is_sent = 1;
                $this->saveRecordByEntity('#__projects_tasks_assignments', $member);

                $email->sendDefinedLayoutEmail('projects.tasks.assignment', $user->email, $parameters);
            }
        }
    }

    public function sendEmailOnStatusChange($form, $task, $id) {

        $email = new Email();
        $factory = new KazistFactory();

        $setting_change = $factory->getSetting('project_task_status_change_email');

        if ($id && $setting_change == $form['status']) {

            $task = ($task != '') ? $task : parent::getRecord($id);

            $query = parent::getQueryBuilder('#__projects_tasks_assignments', 'pta', array('task_id=' . $id));
            $members = $query->loadObjectList();

            foreach ($members as $member) {
                $user = $this->getUserId($member->user_id);
                $parameters = array('task' => $task, 'user' => $user);
                $email->sendDefinedLayoutEmail('projects.tasks.status_change', $user->email, $parameters);
            }

            $this->sendEmailOnStatusChangeToClient($task);
        }
    }

    public function sendEmailOnStatusChangeToClient($task) {

        $email = new Email();

        $project_query = parent::getQueryBuilder('#__projects_projects', 'pp', array('id=' . (int) $task->project_id));
        $project = $project_query->loadObject();

        $member_query = parent::getQueryBuilder('#__projects_clients_members', 'pcm', array('client_id=' . $project->client_id));
        $members = $member_query->loadObjectList();

        foreach ($members as $member) {
            $user = $this->getUserId($member->user_id);
            $parameters = array('task' => $task, 'user' => $user);
            $email->sendDefinedLayoutEmail('projects.tasks.status_change', $user->email, $parameters);
        }
    }

    public function sendEmailOnTaskClosed($form, $task, $id) {

        $email = new Email();

        if (!$form['closed']) {
            return false;
        }

        if ($id) {

            $task = ($task != '') ? $task : parent::getRecord($id);

            $query = parent::getQueryBuilder('#__projects_tasks_assignments', 'pta', array('task_id=' . $id));
            $members = $query->loadObjectList();

            foreach ($members as $member) {
                $user = $this->getUserId($member->user_id);
                $parameters = array('task' => $task, 'user' => $user);
                $email->sendDefinedLayoutEmail('projects.tasks.closed', $user->email, $parameters);
            }

            $this->sendEmailOnTaskClosedToClient($task);
        }
    }

    public function sendEmailOnTaskClosedToClient($task) {

        $email = new Email();

        $project_query = parent::getQueryBuilder('#__projects_projects', 'pp', array('id=' . (int) $task->project_id));
        $project = $project_query->loadObject();

        $member_query = parent::getQueryBuilder('#__projects_clients_members', 'pcm', array('client_id=' . $project->client_id));
        $members = $member_query->loadObjectList();

        foreach ($members as $member) {
            $user = $this->getUserId($member->user_id);
            $parameters = array('task' => $task, 'user' => $user);
            $email->sendDefinedLayoutEmail('projects.tasks.closed', $user->email, $parameters);
        }
    }

    public function getAssignments($record) {

        $where_arr = array();
        $where_arr[] = 'pta.task_id=:task_id';

        $parameter_arr = array('task_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks_assignments', 'pta', $where_arr, $parameter_arr);
        $query->leftJoin('pta', '#__media_media', 'mm', 'uu.avatar = mm.id');
        $query->addSelect('mm.file as avatar_file');
        $query->addSelect('mm.title as avatar_title');

        $records = $query->loadObjectList();

        return $records;
    }

    public function getDocuments($record) {

        $where_arr = array();
        $where_arr[] = 'ptd.task_id=:task_id';

        $parameter_arr = array('task_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks_documents', 'ptd', $where_arr, $parameter_arr);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getSubtasks($record) {

        $where_arr = array();
        $where_arr[] = 'pts.task_id=:task_id';

        $parameter_arr = array('task_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks_subtasks', 'pts', $where_arr, $parameter_arr);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getVotes($record) {

        $where_arr = array();
        $where_arr[] = 'ptv.task_id=:task_id';

        $parameter_arr = array('task_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks_votes', 'ptv', $where_arr, $parameter_arr);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getWatchers($record) {

        $where_arr = array();
        $where_arr[] = 'ptw.task_id=:task_id';

        $parameter_arr = array('task_id' => $record->id);

        $query = parent::getQueryBuilder('#__projects_tasks_watchers', 'ptw', $where_arr, $parameter_arr);

        $records = $query->loadObjectList();

        return $records;
    }

    public function getUserId($user_id) {

        $where_arr = array('uu.id=:user_id');
        $parameter_arr = array('user_id' => $user_id);
        $query = parent::getQueryBuilder('#__users_users', 'uu', $where_arr, $parameter_arr);

        $record = $query->loadObject();

        return $record;
    }

    public function getUserProjects($user_id) {

        $tmp_array = array();

        /* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Get Client Projects xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
        $where_arr = array('pcm.user_id=:user_id');
        $parameter_arr = array('user_id' => $user_id);
        $query = parent::getQueryBuilder('#__projects_projects', 'pp', $where_arr, $parameter_arr);
        $query->select('pp.id');
        $query->leftJoin('pp', '#__projects_clients_members', 'pcm', 'pp.client_id = pcm.client_id');
        $client_records = $query->loadObjectList();

        foreach ($client_records as $key => $record) {
            $tmp_array[] = $record->id;
        }

        /* xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx Get Assigned Projects xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx */
        $where_arr = array('ppm.user_id=:user_id');
        $parameter_arr = array('user_id' => $user_id);
        $query = parent::getQueryBuilder('#__projects_projects_members', 'ppm', $where_arr, $parameter_arr);
        $query->select('ppm.project_id');
        $member_records = $query->loadObjectList();

        foreach ($member_records as $key => $record) {
            $tmp_array[] = $record->project_id;
        }

        $new_tmp_array = array_unique($tmp_array);

        return $new_tmp_array;
    }

}
