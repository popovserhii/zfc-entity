<?php
namespace Popov\ZfcEntity\Model;

use Doctrine\ORM\Mapping as ORM;
use Popov\ZfcCore\Model\DomainAwareTrait;

/**
 * Entity
 * @ORM\Entity(repositoryClass="Popov\ZfcEntity\Model\Repository\EntityRepository")
 * @ORM\Table(name="entity")
 */
class Entity
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
     * @ORM\Column(name="namespace", type="string", length=255, nullable=false)
     */
    private $namespace;

    /**
     * @var string
     * @ORM\Column(name="mnemo", type="string", length=32, nullable=false)
     */
    private $mnemo;

    /**
     * @var integer
     * @ORM\Column(name="hidden", type="smallint", length=1, nullable=false)
     */
    private $hidden = 0;

    /**
     * @var Module
     * @ORM\ManyToOne(targetEntity="Popov\ZfcEntity\Model\Module", inversedBy="entities", cascade={"persist","remove"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="moduleId", referencedColumnName="id")
     * })
     */
    private $module;

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
     * Set namespace
     *
     * @param string $namespace
     * @return Entity
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;

        return $this;
    }

    /**
     * Get namespace
     *
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * Set mnemo
     *
     * @param string $mnemo
     * @return Entity
     */
    public function setMnemo($mnemo)
    {
        $this->mnemo = $mnemo;

        return $this;
    }

    /**
     * Get mnemo
     *
     * @return string
     */
    public function getMnemo()
    {
        return $this->mnemo;
    }

    /**
     * Set hidden
     *
     * @param string $hidden
     * @return Entity
     */
    public function setHidden($hidden)
    {
        $this->hidden = $hidden;

        return $this;
    }

    /**
     * Get hidden
     *
     * @return string
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * @return Module
     */
    public function getModule()
    {
        return $this->module;
    }

    /**
     * @param Module $module
     * @return Entity
     */
    public function setModule($module)
    {
        $this->module = $module;

        return $this;
    }
}

