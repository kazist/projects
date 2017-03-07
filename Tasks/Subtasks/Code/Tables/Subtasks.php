<?php

namespace Projects\Tasks\Subtasks\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subtasks
 *
 * @ORM\Table(name="projects_tasks_subtasks", indexes={@ORM\Index(name="task_id_index", columns={"task_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Subtasks extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="task_id", type="integer", length=11, nullable=false)
     */
    protected $task_id;

    /**
     * @var string
     *
     * @ORM\Column(name="subtask", type="text", nullable=false)
     */
    protected $subtask;

    /**
     * @var integer
     *
     * @ORM\Column(name="closed", type="integer", length=11, nullable=true)
     */
    protected $closed;

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
     * Set task_id
     *
     * @param integer $taskId
     * @return Subtasks
     */
    public function setTaskId($taskId)
    {
        $this->task_id = $taskId;

        return $this;
    }

    /**
     * Get task_id
     *
     * @return integer 
     */
    public function getTaskId()
    {
        return $this->task_id;
    }

    /**
     * Set subtask
     *
     * @param string $subtask
     * @return Subtasks
     */
    public function setSubtask($subtask)
    {
        $this->subtask = $subtask;

        return $this;
    }

    /**
     * Get subtask
     *
     * @return string 
     */
    public function getSubtask()
    {
        return $this->subtask;
    }

    /**
     * Set closed
     *
     * @param integer $closed
     * @return Subtasks
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
