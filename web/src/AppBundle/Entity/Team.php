<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="teams")
 * @ORM\HasLifecycleCallbacks
 */
class Team implements \JsonSerializable
{
    use BasicEntityTrait;
    use ActivableTrait;
    use TimestampableEntity;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team")
     */
    private $players;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
     */
    private $game;


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
        $p->setTeam($this);
    }

    /**
     * @param Player $p
     */
    public function removePlayer(Player $p)
    {
        $this->getPlayers()->removeElement($p);
        $p->setTeam(null);
    }


    public function __toString()
    {
        return $this->getName();
    }

    function jsonSerialize()
    {
        return [
            'id'      => $this->getId(),
            'name'    => $this->getName(),
            'players' => $this->getPlayers()->toArray(),
        ];
    }

    /**
     * @return mixed
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param mixed $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }

}