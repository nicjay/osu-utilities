<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="util_rate_adjustment_dev", schema="sym")
 */
class RateAdjustment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoiceMonth;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoiceYear;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $utilityType;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @return mixed
     */
    public function getInvoiceMonth()
    {
        return $this->invoiceMonth;
    }

    /**
     * @param mixed $invoiceMonth
     */
    public function setInvoiceMonth($invoiceMonth)
    {
        $this->invoiceMonth = $invoiceMonth;
    }

    /**
     * @return mixed
     */
    public function getInvoiceYear()
    {
        return $this->invoiceYear;
    }

    /**
     * @param mixed $invoiceYear
     */
    public function setInvoiceYear($invoiceYear)
    {
        $this->invoiceYear = $invoiceYear;
    }

    /**
     * @return mixed
     */
    public function getUtilityType()
    {
        return $this->utilityType;
    }

    /**
     * @param mixed $utilityType
     */
    public function setUtilityType($utilityType)
    {
        $this->utilityType = $utilityType;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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





}

