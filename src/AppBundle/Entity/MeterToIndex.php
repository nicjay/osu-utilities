<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_meter_indexes_dev", schema="sym")
 */
class MeterToIndex
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $meter_id;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $funding_index;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $start_date;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $end_date;

    /**
     * @ORM\Column(type="boolean", nullable=false)
     */
    private $is_active;

    /**
     * @ORM\Column(type="integer", nullable=false)
     */
    private $percentage;

    /**
     * @ORM\Column(type="string", length=10, nullable=false)
     */
    private $activity_code;





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
     * Set meterId
     *
     * @param integer $meterId
     *
     * @return MeterToIndex
     */
    public function setMeterId($meterId)
    {
        $this->meter_id = $meterId;
    
        return $this;
    }

    /**
     * Get meterId
     *
     * @return integer
     */
    public function getMeterId()
    {
        return $this->meter_id;
    }

    /**
     * Set fundingIndex
     *
     * @param string $fundingIndex
     *
     * @return MeterToIndex
     */
    public function setFundingIndex($fundingIndex)
    {
        $this->funding_index = $fundingIndex;
    
        return $this;
    }

    /**
     * Get fundingIndex
     *
     * @return string
     */
    public function getFundingIndex()
    {
        return $this->funding_index;
    }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return MeterToIndex
     */
    public function setStartDate($startDate)
    {
        $this->start_date = $startDate;
    
        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return MeterToIndex
     */
    public function setEndDate($endDate)
    {
        $this->end_date = $endDate;
    
        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return MeterToIndex
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Set percentage
     *
     * @param integer $percentage
     *
     * @return MeterToIndex
     */
    public function setPercentage($percentage)
    {
        $this->percentage = $percentage;
    
        return $this;
    }

    /**
     * Get percentage
     *
     * @return integer
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * Set activityCode
     *
     * @param string $activityCode
     *
     * @return MeterToIndex
     */
    public function setActivityCode($activityCode)
    {
        $this->activity_code = $activityCode;
    
        return $this;
    }

    /**
     * Get activityCode
     *
     * @return string
     */
    public function getActivityCode()
    {
        return $this->activity_code;
    }
}
