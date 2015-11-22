<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Zantolov\MediaBundle\Entity\Traits\ImageableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="messages")
 * @ORM\HasLifecycleCallbacks
 */
class Message implements \JsonSerializable
{
    use BasicEntityTrait;
    use TimestampableEntity;

    /**
     * @var Player
     * @ORM\ManyToOne(targetEntity="Player")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id", nullable=false)
     */
    private $player;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $message;

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
     * @return float
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @param float $lat
     */
    public function setLat($lat) {
        $this->lat = $lat;
    }

    /**
     * @return float
     */
    public function getLng() {
        return $this->lng;
    }

    /**
     * @param float $lng
     */
    public function setLng($lng) {
        $this->lng = $lng;
    }

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
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    function jsonSerialize()
    {
        return [
            'id'      => $this->getId(),
            'player'  => $this->getPlayer(),
            'message' => $this->getMessage(),
            'lat'     => $this->getLat(),
            'lng'     => $this->getLng(),
        ];
    }


}