<?php

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="utility_rate_adjustments_dev", schema="sym")
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
     * @Assert\DateTime()
     */
    private $monthAndYear;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoiceMonth;

    /**
     * @ORM\Column(type="integer")
     */
    private $invoiceYear;

    /**
     * @Assert\Type(type="string")
     * @ORM\Column(type="string", length=128)
     */
    private $utilityType;

    /**
     * @Assert\Type(type="string")
     * @ORM\Column(type="string", length=128)
     */
    private $description;

    /**
     * @Assert\DateTime()
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @return mixed
     */
    public function getMonthAndYear()
    {
        return $this->monthAndYear;
    }

    /**
     * @param mixed $monthAndYear
     */
    public function setMonthAndYear($monthAndYear)
    {
        $this->monthAndYear = $monthAndYear;
    }


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






    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
