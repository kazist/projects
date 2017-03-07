<?php

/*
 * This file is part of Kazist Framework.
 * (c) Dedan Irungu <irungudedan@gmail.com>
 *  For the full copyright and license information, please view the LICENSE file that was distributed with this source code.
 * 
 */

namespace Projects\Tasks\Code\Listeners;

defined('KAZIST') or exit('Not Kazist Framework');

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Kazist\KazistFactory;
use Kazist\Event\CRUDEvent;
use Kazist\Service\System\System;
use Kazist\Service\Database\Query;
use Kazist\Service\Email\Email;

class TasksListener implements EventSubscriberInterface {

    public $container = '';

    public function onCommentAferSave(CRUDEvent $event) {


        global $sc;
        $this->container = $sc;

        $query = new Query();
        $system = new System();
        $factory = new KazistFactory();
        $comment = $event->getRecord();

        $subset_id = $comment->getSubsetId();
        $record_id = $comment->getRecordId();

        $required_subset_id = $system->getSubsetId('projects/tasks');

        if ($required_subset_id == $subset_id) {

            $query->select('COUNT(pt.id) AS total');
            $query->from('#__notification_comments', 'pt');
            $query->where('pt.subset_id=:subset_id');
            $query->andWhere('pt.record_id=:record_id');
            $query->setParameter('record_id', $record_id);
            $query->setParameter('subset_id', $subset_id);
            $total_comments = $query->loadResult();

            $data_obj = new \stdClass();
            $data_obj->id = $record_id;
            $data_obj->total_comments = $total_comments;

            $factory->saveRecord('#__projects_tasks', $data_obj);

            $this->sendEmailToTaskAssigned($comment);
        }
    }

    public function sendEmailToTaskAssigned($comment) {

        $email = new Email();
        $factory = new KazistFactory();

        $comment_id = $comment->getId();
        $task_id = $comment->getRecordId();

        $query = $factory->getQueryBuilder('#__projects_tasks_assignments', 'pta', array('task_id=:task_id'), array('task_id' => $task_id));
        $members = $query->loadObjectList();

        $query = $factory->getQueryBuilder('#__projects_tasks', 'pt', array('id=:id'), array('id' => $task_id));
        $task = $query->loadObject();

        $query = $factory->getQueryBuilder('#__projects_projects', 'pp', array('id=:id'), array('id' => $task->project_id));
        $project = $query->loadObject();

        $query = $factory->getQueryBuilder('#__notification_comments', 'nc', array('id=:id'), array('id' => $comment_id));
        $new_comment = $query->loadObject();

        foreach ($members as $member) {
            $user = $this->getUserId($member->user_id);
            $parameters = array('project' => $project, 'task' => $task, 'user' => $user, 'comment' => $new_comment);
            $email->sendDefinedLayoutEmail('projects.tasks.assignment', $user->email, $parameters);
        }

        $this->sendEmailToClient($project, $task, $new_comment);
    }

    public function sendEmailToClient($project, $task, $comment) {

        $email = new Email();
        $factory = new KazistFactory();

        $member_query = $factory->getQueryBuilder('#__projects_clients_members', 'pcm', array('client_id=' . $project->client_id));
        $members = $member_query->loadObjectList();

        foreach ($members as $member) {
            $user = $this->getUserId($member->user_id);
            $parameters = array('project' => $project, 'task' => $task, 'user' => $user, 'comment' => $comment);
            $email->sendDefinedLayoutEmail('projects.tasks.comments', $user->email, $parameters);
        }
    }

    public function getUserId($user_id) {

        $factory = new KazistFactory();

        $query = $factory->getQueryBuilder('#__users_users', 'uu', array('uu.id=:user_id'), array('user_id' => $user_id));

        $record = $query->loadObject();

        return $record;
    }

    public static function getSubscribedEvents() {
        return array(
            'notification.comments.after.save' => 'onCommentAferSave',
        );
    }

}
