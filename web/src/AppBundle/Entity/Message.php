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
            'player'  => $this->getPlayer(),
            'message' => $this->getMessage(),
        ];
    }


}