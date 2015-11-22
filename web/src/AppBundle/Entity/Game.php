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

    /*
     * Returns array with alive/dead/total team member info
     */
    function getKillCount()
    {
        $result = [
            'team1' => [
                'total' => 0,
                'dead' => 0,
                'alive' => 0,
            ],
            'team2' => [
                'total' => 0,
                'dead' => 0,
                'alive' => 0,
            ],
        ];
        foreach($this->getTeam1()->getPlayers() as $player) {
            $result['team1']['total']++;
            if($player->isLive() == true)
                $result['team1']['alive']++;
            else
                $result['team1']['dead']++;
        }
        foreach($this->getTeam2()->getPlayers() as $player) {
            $result['team2']['total']++;
            if($player->isLive() == true)
                $result['team2']['alive']++;
            else
                $result['team2']['dead']++;
        }
        return $result;
    }

    function getRegionOwners()
    {
        return [
            1 => $this->getOwnerRegion1() != null ? $this->getOwnerRegion1()->getId() : null,
            2 => $this->getOwnerRegion2() != null ? $this->getOwnerRegion2()->getId() : null,
            3 => $this->getOwnerRegion3() != null ? $this->getOwnerRegion3()->getId() : null,
            4 => $this->getOwnerRegion4() != null ? $this->getOwnerRegion4()->getId() : null,
        ];
    }

    function getRegionCount()
    {
        $result = [
            'team1' => 0,
            'team2' => 0,
        ];

        $owners = $this->getRegionOwners();
        foreach($owners as $zone => $owner) {
            switch($owner) {
                case 1:
                    $result['team1'] += 1;
                    break;
                case 2:
                    $result['team2'] += 1;
                    break;
            }
        }

        return $result;

    }

    function getScore()
    {
        $result = [
            'team1' => 0,
            'team2' => 0,
        ];

        // Kill score
        $killCount = $this->getKillCount();
        $result['team1'] = $killCount['team2']['dead'] * 2;
        $result['team2'] = $killCount['team1']['dead'] * 2;

        // Zone score
        $owners = $this->getRegionOwners();
        foreach($owners as $zone => $owner) {
            switch($owner) {
                case 1:
                    $result['team1'] += 5;
                    break;
                case 2:
                    $result['team2'] += 5;
                    break;
            }
        }

        return $result;
    }

    function getOdds()
    {
        $count = $this->getKillCount();
        $result = [
            'team1' => round(0.1 + ($count['team2']['alive'] / $count['team1']['alive']) * 2, 2),
            'team2' => round(0.1 + ($count['team1']['alive'] / $count['team2']['alive']) * 2, 2),
        ];
        return $result;
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
            'regions'      => $this->getRegionOwners(),
            'regionCount'  => $this->getRegionCount(),
            'playerCount'  => $this->getKillCount(),
            'score'        => $this->getScore(),
            'endTimeStamp' => $this->getEndTimestamp()->getTimestamp(),
            'odds'         => $this->getOdds(),
        ];
    }


}