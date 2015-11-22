<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="games")
 * @ORM\HasLifecycleCallbacks
 */
class Game implements \JsonSerializable
{
    use BasicEntityTrait;
    use ActivableTrait;
    use TimestampableEntity;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team1_id", referencedColumnName="id")
     */
    private $team1;


    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team2_id", referencedColumnName="id")
     */
    private $team2;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="winner_id", referencedColumnName="id")
     */
    private $winner;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $ownerRegion1;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $ownerRegion2;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $ownerRegion3;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(referencedColumnName="id")
     */
    private $ownerRegion4;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    protected $endTimestamp;


    private $gameDuration;

    public function __construct()
    {
    }

    /**
     * @return Team
     */
    public function getTeam1()
    {
        return $this->team1;
    }

    /**
     * @param mixed $team1
     */
    public function setTeam1($team1)
    {
        $this->team1 = $team1;
        if ($team1 instanceof Team) {
            $team1->setGame($this);
        }
    }

    /**
     * @return mixed
     */
    public function getTeam2()
    {
        return $this->team2;
    }

    /**
     * @param mixed $team2
     */
    public function setTeam2($team2)
    {
        if ($team2 instanceof Team) {
            $team2->setGame($this);
        }
        
        $this->team2 = $team2;
    }

    /**
     * @return mixed
     */
    public function getWinner()
    {
        return $this->winner;
    }

    /**
     * @param mixed $winner
     */
    public function setWinner($winner)
    {
        $this->winner = $winner;
    }

    /**
     * @return mixed
     */
    public function getOwnerRegion1()
    {
        return $this->ownerRegion1;
    }

    /**
     * @param mixed $ownerRegion1
     */
    public function setOwnerRegion1($ownerRegion1)
    {
        $this->ownerRegion1 = $ownerRegion1;
    }

    /**
     * @return mixed
     */
    public function getOwnerRegion2()
    {
        return $this->ownerRegion2;
    }

    /**
     * @param mixed $ownerRegion2
     */
    public function setOwnerRegion2($ownerRegion2)
    {
        $this->ownerRegion2 = $ownerRegion2;
    }

    /**
     * @return mixed
     */
    public function getOwnerRegion3()
    {
        return $this->ownerRegion3;
    }

    /**
     * @param mixed $ownerRegion3
     */
    public function setOwnerRegion3($ownerRegion3)
    {
        $this->ownerRegion3 = $ownerRegion3;
    }

    /**
     * @return mixed
     */
    public function getOwnerRegion4()
    {
        return $this->ownerRegion4;
    }

    /**
     * @param mixed $ownerRegion4
     */
    public function setOwnerRegion4($ownerRegion4)
    {
        $this->ownerRegion4 = $ownerRegion4;
    }

    /**
     * @return EndTimestamp
     */
    public function getEndTimestamp()
    {
        return $this->endTimestamp;
    }

    /**
     * @param mixed $endTimestamp
     */
    public function setEndTimeStamp($endTimestamp)
    {
        $this->endTimestamp = $endTimestamp;
    }

    function jsonSerialize()
    {
        return [
            'id'           => $this->getId(),
            'team1'        => $this->getTeam1(),
            'team2'        => $this->getTeam2(),
            'winner'       => $this->getWinner(),
            'ownerRegion1' => $this->getOwnerRegion1(),
            'ownerRegion2' => $this->getOwnerRegion2(),
            'ownerRegion3' => $this->getOwnerRegion3(),
            'ownerRegion4' => $this->getOwnerRegion4(),
            'endTimeStamp' => $this->getEndTimestamp()->getTimestamp(),
        ];
    }


}