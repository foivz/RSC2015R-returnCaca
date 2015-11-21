<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="games")
 * @ORM\HasLifecycleCallbacks
 */
class Game
{
    use BasicEntityTrait;
    use ActivableTrait;

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
     * @ORM\OneToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="winner_id", referencedColumnName="id")
     */
    private $winner;

    public function __construct()
    {
    }

    /**
     * @return mixed
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
}