<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="teams")
 * @ORM\HasLifecycleCallbacks
 */
class Team
{
    use BasicEntityTrait;
    use ActivableTrait;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Player", mappedBy="teams")
     */
    private $players;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;


    public function __construct()
    {
        $this->players = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return ArrayCollection
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * @param Player $p
     */
    public function addPlayer(Player $p)
    {
        $this->getPlayers()->add($p);
    }

    /**
     * @param Player $p
     */
    public function removePlayer(Player $p)
    {
        $this->getPlayers()->removeElement($p);

    }
}