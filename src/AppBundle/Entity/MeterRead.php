<?php

namespace AppBundle\Entity;

/**
 * Created by PhpStorm.
 * User: jordan_n
 * Date: 9/22/2015
 * Time: 1:11 PM
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_meter_reads_dev", schema="sym")
 */
class MeterRead
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="meter_id", type="integer", nullable=false)
     */
    protected $meterId;

    /**
     * @ORM\Column(name="read_date", type="datetime", nullable=true)
     */
    protected $readDate;

    /**
     * @ORM\Column(name="meter_read", type="decimal", precision=16, scale=2, nullable=true)
     */
    protected $meterRead;

    /**
     * @ORM\Column(name="peak", type="decimal", precision=16, scale=2, nullable=true)
     */
    protected $peak;

    /**
     * @ORM\Column(name="consumption", type="decimal", precision=16, scale=2, nullable=true)
     */
    protected $consumption;

    /**
     * @ORM\Column(name="total_dollars", type="decimal", precision=16, scale=2, nullable=true)
     */
    protected $totalDollars;

    /**
     * @ORM\Column(type="datetime", scale=8, nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", scale=8, nullable=true)
     */
    private $modified;


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
     * @return MeterRead
     */
    public function setMeterId($meterId)
    {
        $this->meterId = $meterId;
    
        return $this;
    }

    /**
     * Get meterId
     *
     * @return integer
     */
    public function getMeterId()
    {
        return $this->meterId;
    }

    /**
     * Set readDate
     *
     * @param \DateTime $readDate
     *
     * @return MeterRead
     */
    public function setReadDate($readDate)
    {
        $this->readDate = $readDate;
    
        return $this;
    }

    /**
     * Get readDate
     *
     * @return \DateTime
     */
    public function getReadDate()
    {
        return $this->readDate;
    }

    /**
     * Set meterRead
     *
     * @param string $meterRead
     *
     * @return MeterRead
     */
    public function setMeterRead($meterRead)
    {
        $this->meterRead = $meterRead;
    
        return $this;
    }

    /**
     * Get meterRead
     *
     * @return string
     */
    public function getMeterRead()
    {
        return $this->meterRead;
    }

    /**
     * Set peak
     *
     * @param string $peak
     *
     * @return MeterRead
     */
    public function setPeak($peak)
    {
        $this->peak = $peak;
    
        return $this;
    }

    /**
     * Get peak
     *
     * @return string
     */
    public function getPeak()
    {
        return $this->peak;
    }

    /**
     * Set consumption
     *
     * @param string $consumption
     *
     * @return MeterRead
     */
    public function setConsumption($consumption)
    {
        $this->consumption = $consumption;
    
        return $this;
    }

    /**
     * Get consumption
     *
     * @return string
     */
    public function getConsumption()
    {
        return $this->consumption;
    }

    /**
     * Set totalDollars
     *
     * @param string $totalDollars
     *
     * @return MeterRead
     */
    public function setTotalDollars($totalDollars)
    {
        $this->totalDollars = $totalDollars;
    
        return $this;
    }

    /**
     * Get totalDollars
     *
     * @return string
     */
    public function getTotalDollars()
    {
        return $this->totalDollars;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return MeterRead
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     *
     * @return MeterRead
     */
    public function setModified($modified)
    {
        $this->modified = $modified;
    
        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime
     */
    public function getModified()
    {
        return $this->modified;
    }
}
