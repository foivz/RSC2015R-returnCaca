<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Zantolov\MediaBundle\Entity\Traits\ImageableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="game_stats", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="stat_idx", columns={"player_id", "game_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class GameStat
{
    use BasicEntityTrait;
    use ActivableTrait;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", nullable=false)
     */
    private $player;

    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=false)
     */
    private $game;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isLive;

    /**
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer($player)
    {
        $this->player = $player;
    }

    /**
     * @return boolean
     */
    public function isIsLive()
    {
        return $this->isLive;
    }

    /**
     * @param boolean $isLive
     */
    public function setIsLive($isLive)
    {
        $this->isLive = $isLive;
    }

    /**
     * @return Game
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * @param Game $game
     */
    public function setGame($game)
    {
        $this->game = $game;
    }


}