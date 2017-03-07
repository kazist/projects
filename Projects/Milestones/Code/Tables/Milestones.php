<?php

namespace Projects\Projects\Milestones\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Milestones
 *
 * @ORM\Table(name="projects_projects_milestones")
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Milestones extends \Kazist\Table\BaseTable
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
     * @ORM\Column(name="milestone_id", type="integer", length=11, nullable=false)
     */
    protected $milestone_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="project_id", type="integer", length=11, nullable=false)
     */
    protected $project_id;

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
     * Set milestone_id
     *
     * @param integer $milestoneId
     * @return Milestones
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
     * Set project_id
     *
     * @param integer $projectId
     * @return Milestones
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
