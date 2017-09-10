<?php
namespace Popov\ZfcEntity\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Popov\ZfcCore\Model\DomainAwareTrait;

/**
 * Module
 * @ORM\Entity()
 * @ORM\Table(name="module")
 */
class Module
{
    use DomainAwareTrait;

    /**
     * @var integer
     * @ORM\Column(name="id", type="integer", nullable=false, options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="name", type="string", unique=true, length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(name="mnemo", type="string", unique=true, length=255, nullable=false)
     */
    private $mnemo;

    /**
     * @var Entity[]
     * @ORM\OneToMany(targetEntity="Popov\ZfcEntity\Model\Entity", mappedBy="module", cascade={"persist","remove"})
     */
    private $entities;

    public function __construct()
    {
        $this->entities = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param int $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getMnemo()
    {
        return $this->mnemo;
    }

    /**
     * @param string $mnemo
     * @return Module
     */
    public function setMnemo($mnemo)
    {
        $this->mnemo = $mnemo;

        return $this;
    }

    /**
     * @return Entity[]
     */
    public function getEntities()
    {
        return $this->entities;
    }

    /**
     * @param Entity[] $entities
     * @return self
     */
    public function setEntities($entities)
    {
        $this->entities = $entities;

        return $this;
    }

    /**
     * @param $entities
     */
    public function addEntities($entities)
    {
        foreach ($entities as $entity) {
            $this->entities->add($entity);
        }
    }

    /**
     * @param $entities
     */
    public function removeInvoiceProducts($entities)
    {
        foreach ($entities as $entity) {
            $this->entities->removeElement($entity);
        }
    }
}
