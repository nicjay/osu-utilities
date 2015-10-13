<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="util_meter_to_index")
 */
class MeterToIndex
{
    /**
     * @ORM\Column(type="integer")
     */
    private $meterId;

    /**
     * @ORM\Column(type="integer")
     */
    private $index;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @return mixed
     */
    public function getMeterId()
    {
        return $this->meterId;
    }

    /**
     * @param mixed $meterId
     */
    public function setMeterId($meterId)
    {
        $this->meterId = $meterId;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }




}

