<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Zantolov\MediaBundle\Entity\Traits\ImageableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="locations")
 * @ORM\HasLifecycleCallbacks
 */
class Location implements \JsonSerializable
{
    use BasicEntityTrait;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", nullable=false)
     */
    private $player;


    /**
     * @var Game
     * @ORM\ManyToOne(targetEntity="Game")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id", nullable=true)
     */
    private $game;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14, nullable=true)
     */
    private $lat;


    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14, nullable=true)
     */
    private $lng;

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
     * @return float
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
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

    function jsonSerialize()
    {
        return [
            'lat'  => $this->getLat(),
            'lng'  => $this->getLng(),
            'game' => !empty($this->getGame()) ? $this->getGame()->getId() : null,
        ];
    }


}