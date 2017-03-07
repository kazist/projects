<?php

namespace Projects\Tasks\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tasks
 *
 * @ORM\Table(name="projects_tasks", indexes={@ORM\Index(name="project_id_index", columns={"project_id"}), @ORM\Index(name="milestone_id_index", columns={"milestone_id"}), @ORM\Index(name="priority_id_index", columns={"priority_id"}), @ORM\Index(name="type_id_index", columns={"type_id"}), @ORM\Index(name="duration_id_index", columns={"duration_id"}), @ORM\Index(name="status_index", columns={"status"}), @ORM\Index(name="status_changed_by_index", columns={"status_changed_by"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Tasks extends \Kazist\Table\BaseTable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer", length=11, nullable=true)
     */
    protected $project_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="milestone_id", type="integer", length=11, nullable=true)
     */
    protected $milestone_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="priority_id", type="integer", length=11, nullable=true)
     */
    protected $priority_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="type_id", type="integer", length=11, nullable=true)
     */
    protected $type_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration_id", type="integer", length=11, nullable=true)
     */
    protected $duration_id;

    /**
     * @var string
     *
     * @ORM\Column(name="task", type="text", nullable=true)
     */
    protected $task;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true)
     */
    protected $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=true)
     */
    protected $start_time;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=true)
     */
    protected $end_time;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=true)
     */
    protected $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_subtasks", type="integer", length=11, nullable=true)
     */
    protected $total_subtasks;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_comments", type="integer", length=11, nullable=true)
     */
    protected $total_comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="completed_subtasks", type="integer", length=11, nullable=true)
     */
    protected $completed_subtasks;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_status_changed", type="datetime", nullable=true)
     */
    protected $date_status_changed;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_changed_by", type="integer", length=11, nullable=true)
     */
    protected $status_changed_by;

    /**
     * @var integer
     *
     * @ORM\Column(name="closed", type="integer", length=11, nullable=true)
     */
    protected $closed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closed_date", type="datetime", nullable=true)
     */
    protected $closed_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=true)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=true)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=true)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=true)
     */
    protected $date_modified;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Tasks
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set project_id
     *
     * @param integer $projectId
     * @return Tasks
     */
    public function setProjectId($projectId)
    {
        $this->project_id = $projectId;

        return $this;
    }

    /**
     * Get project_id
     *
     * @return integer 
     */
    public function getProjectId()
    {
        return $this->project_id;
    }

    /**
     * Set milestone_id
     *
     * @param integer $milestoneId
     * @return Tasks
     */
    public function setMilestoneId($milestoneId)
    {
        $this->milestone_id = $milestoneId;

        return $this;
    }

    /**
     * Get milestone_id
     *
     * @return integer 
     */
    public function getMilestoneId()
    {
        return $this->milestone_id;
    }

    /**
     * Set priority_id
     *
     * @param integer $priorityId
     * @return Tasks
     */
    public function setPriorityId($priorityId)
    {
        $this->priority_id = $priorityId;

        return $this;
    }

    /**
     * Get priority_id
     *
     * @return integer 
     */
    public function getPriorityId()
    {
        return $this->priority_id;
    }

    /**
     * Set type_id
     *
     * @param integer $typeId
     * @return Tasks
     */
    public function setTypeId($typeId)
    {
        $this->type_id = $typeId;

        return $this;
    }

    /**
     * Get type_id
     *
     * @return integer 
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * Set duration_id
     *
     * @param integer $durationId
     * @return Tasks
     */
    public function setDurationId($durationId)
    {
        $this->duration_id = $durationId;

        return $this;
    }

    /**
     * Get duration_id
     *
     * @return integer 
     */
    public function getDurationId()
    {
        return $this->duration_id;
    }

    /**
     * Set task
     *
     * @param string $task
     * @return Tasks
     */
    public function setTask($task)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return string 
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Tasks
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set start_time
     *
     * @param \DateTime $startTime
     * @return Tasks
     */
    public function setStartTime($startTime)
    {
        $this->start_time = $startTime;

        return $this;
    }

    /**
     * Get start_time
     *
     * @return \DateTime 
     */
    public function getStartTime()
    {
        return $this->start_time;
    }

    /**
     * Set end_time
     *
     * @param \DateTime $endTime
     * @return Tasks
     */
    public function setEndTime($endTime)
    {
        $this->end_time = $endTime;

        return $this;
    }

    /**
     * Get end_time
     *
     * @return \DateTime 
     */
    public function getEndTime()
    {
        return $this->end_time;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return Tasks
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set total_subtasks
     *
     * @param integer $totalSubtasks
     * @return Tasks
     */
    public function setTotalSubtasks($totalSubtasks)
    {
        $this->total_subtasks = $totalSubtasks;

        return $this;
    }

    /**
     * Get total_subtasks
     *
     * @return integer 
     */
    public function getTotalSubtasks()
    {
        return $this->total_subtasks;
    }

    /**
     * Set total_comments
     *
     * @param integer $totalComments
     * @return Tasks
     */
    public function setTotalComments($totalComments)
    {
        $this->total_comments = $totalComments;

        return $this;
    }

    /**
     * Get total_comments
     *
     * @return integer 
     */
    public function getTotalComments()
    {
        return $this->total_comments;
    }

    /**
     * Set completed_subtasks
     *
     * @param integer $completedSubtasks
     * @return Tasks
     */
    public function setCompletedSubtasks($completedSubtasks)
    {
        $this->completed_subtasks = $completedSubtasks;

        return $this;
    }

    /**
     * Get completed_subtasks
     *
     * @return integer 
     */
    public function getCompletedSubtasks()
    {
        return $this->completed_subtasks;
    }

    /**
     * Set date_status_changed
     *
     * @param \DateTime $dateStatusChanged
     * @return Tasks
     */
    public function setDateStatusChanged($dateStatusChanged)
    {
        $this->date_status_changed = $dateStatusChanged;

        return $this;
    }

    /**
     * Get date_status_changed
     *
     * @return \DateTime 
     */
    public function getDateStatusChanged()
    {
        return $this->date_status_changed;
    }

    /**
     * Set status_changed_by
     *
     * @param integer $statusChangedBy
     * @return Tasks
     */
    public function setStatusChangedBy($statusChangedBy)
    {
        $this->status_changed_by = $statusChangedBy;

        return $this;
    }

    /**
     * Get status_changed_by
     *
     * @return integer 
     */
    public function getStatusChangedBy()
    {
        return $this->status_changed_by;
    }

    /**
     * Set closed
     *
     * @param integer $closed
     * @return Tasks
     */
    public function setClosed($closed)
    {
        $this->closed = $closed;

        return $this;
    }

    /**
     * Get closed
     *
     * @return integer 
     */
    public function getClosed()
    {
        return $this->closed;
    }

    /**
     * Set closed_date
     *
     * @param \DateTime $closedDate
     * @return Tasks
     */
    public function setClosedDate($closedDate)
    {
        $this->closed_date = $closedDate;

        return $this;
    }

    /**
     * Get closed_date
     *
     * @return \DateTime 
     */
    public function getClosedDate()
    {
        return $this->closed_date;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}
