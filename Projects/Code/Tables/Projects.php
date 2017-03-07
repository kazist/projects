<?php

namespace Projects\Projects\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Projects
 *
 * @ORM\Table(name="projects_projects", indexes={@ORM\Index(name="leader_id_index", columns={"leader_id"}), @ORM\Index(name="category_id_index", columns={"category_id"}), @ORM\Index(name="client_id_index", columns={"client_id"}), @ORM\Index(name="duration_id_index", columns={"duration_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Projects extends \Kazist\Table\BaseTable
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
     * @var integer
     *
     * @ORM\Column(name="leader_id", type="integer", length=11, nullable=true)
     */
    protected $leader_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", length=11, nullable=true)
     */
    protected $category_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="client_id", type="integer", length=11, nullable=true)
     */
    protected $client_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="duration_id", type="integer", length=11, nullable=true)
     */
    protected $duration_id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=false)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

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
     * @var integer
     *
     * @ORM\Column(name="completed", type="integer", length=11, nullable=true)
     */
    protected $completed;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_components", type="integer", length=11, nullable=true)
     */
    protected $total_components;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_tasks", type="integer", length=11, nullable=true)
     */
    protected $total_tasks;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_completed", type="integer", length=11, nullable=true)
     */
    protected $total_completed;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_comments", type="integer", length=11, nullable=true)
     */
    protected $total_comments;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

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
     * Set leader_id
     *
     * @param integer $leaderId
     * @return Projects
     */
    public function setLeaderId($leaderId)
    {
        $this->leader_id = $leaderId;

        return $this;
    }

    /**
     * Get leader_id
     *
     * @return integer 
     */
    public function getLeaderId()
    {
        return $this->leader_id;
    }

    /**
     * Set category_id
     *
     * @param integer $categoryId
     * @return Projects
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set client_id
     *
     * @param integer $clientId
     * @return Projects
     */
    public function setClientId($clientId)
    {
        $this->client_id = $clientId;

        return $this;
    }

    /**
     * Get client_id
     *
     * @return integer 
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Set duration_id
     *
     * @param integer $durationId
     * @return Projects
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
     * Set title
     *
     * @param string $title
     * @return Projects
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
     * Set description
     *
     * @param string $description
     * @return Projects
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set start_time
     *
     * @param \DateTime $startTime
     * @return Projects
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
     * @return Projects
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
     * Set completed
     *
     * @param integer $completed
     * @return Projects
     */
    public function setCompleted($completed)
    {
        $this->completed = $completed;

        return $this;
    }

    /**
     * Get completed
     *
     * @return integer 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set total_components
     *
     * @param integer $totalComponents
     * @return Projects
     */
    public function setTotalComponents($totalComponents)
    {
        $this->total_components = $totalComponents;

        return $this;
    }

    /**
     * Get total_components
     *
     * @return integer 
     */
    public function getTotalComponents()
    {
        return $this->total_components;
    }

    /**
     * Set total_tasks
     *
     * @param integer $totalTasks
     * @return Projects
     */
    public function setTotalTasks($totalTasks)
    {
        $this->total_tasks = $totalTasks;

        return $this;
    }

    /**
     * Get total_tasks
     *
     * @return integer 
     */
    public function getTotalTasks()
    {
        return $this->total_tasks;
    }

    /**
     * Set total_completed
     *
     * @param integer $totalCompleted
     * @return Projects
     */
    public function setTotalCompleted($totalCompleted)
    {
        $this->total_completed = $totalCompleted;

        return $this;
    }

    /**
     * Get total_completed
     *
     * @return integer 
     */
    public function getTotalCompleted()
    {
        return $this->total_completed;
    }

    /**
     * Set total_comments
     *
     * @param integer $totalComments
     * @return Projects
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
     * Set published
     *
     * @param integer $published
     * @return Projects
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
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
