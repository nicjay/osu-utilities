<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="util_meter")
 */
class Meter
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $property;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $property2;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $property3;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $size;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=128)
     */
    private $ownedBy;


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
     * Set type
     *
     * @param string $type
     *
     * @return Meter
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set property
     *
     * @param string $property
     *
     * @return Meter
     */
    public function setProperty($property)
    {
        $this->property = $property;
    
        return $this;
    }

    /**
     * Get property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set property2
     *
     * @param string $property2
     *
     * @return Meter
     */
    public function setProperty2($property2)
    {
        $this->property2 = $property2;
    
        return $this;
    }

    /**
     * Get property2
     *
     * @return string
     */
    public function getProperty2()
    {
        return $this->property2;
    }

    /**
     * Set property3
     *
     * @param string $property3
     *
     * @return Meter
     */
    public function setProperty3($property3)
    {
        $this->property3 = $property3;
    
        return $this;
    }

    /**
     * Get property3
     *
     * @return string
     */
    public function getProperty3()
    {
        return $this->property3;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return Meter
     */
    public function setSize($size)
    {
        $this->size = $size;
    
        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Meter
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
     * Set ownedBy
     *
     * @param string $ownedBy
     *
     * @return Meter
     */
    public function setOwnedBy($ownedBy)
    {
        $this->ownedBy = $ownedBy;
    
        return $this;
    }

    /**
     * Get ownedBy
     *
     * @return string
     */
    public function getOwnedBy()
    {
        return $this->ownedBy;
    }
}

