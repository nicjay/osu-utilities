<?php

namespace AppBundle\Entity;

/**
 * Created by PhpStorm.
 * User: jordan_n
 * Date: 9/22/2015
 * Time: 1:11 PM
 */
class MeterRead
{
    protected $id;
    protected $date;
    protected $rate;
    protected $kilowatts;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return mixed
     */
    public function getKilowatts()
    {
        return $this->kilowatts;
    }

    /**
     * @param mixed $kilowatts
     */
    public function setKilowatts($kilowatts)
    {
        $this->kilowatts = $kilowatts;
    }



}