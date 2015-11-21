<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Zantolov\AppBundle\Entity\Traits\ActivableTrait;
use Zantolov\AppBundle\Entity\Traits\BasicEntityTrait;
use Zantolov\MediaBundle\Entity\Traits\ImageableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity ()
 * @ORM\Table(name="players", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="user_idx", columns={"user_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Player
{
    use BasicEntityTrait;
    use ImageableTrait;
    use ActivableTrait;

    /**
     * @var \Zantolov\AppBundle\Entity\User
     * @ORM\OneToOne(targetEntity="Zantolov\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="players")
     * @ORM\JoinTable(name="players_teams")
     */
    private $teams;


    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14)
     */
    private $score;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $alias;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $level;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14)
     */
    private $calories;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $steps;


    public function __construct()
    {
        $this->teams = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param mixed $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getCalories()
    {
        return $this->calories;
    }

    /**
     * @param mixed $calories
     */
    public function setCalories($calories)
    {
        $this->calories = $calories;
    }

    /**
     * @return mixed
     */
    public function getSteps()
    {
        return $this->steps;
    }

    /**
     * @param mixed $steps
     */
    public function setSteps($steps)
    {
        $this->steps = $steps;
    }

    /**
     * @return ArrayCollection
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param Team $team
     */
    public function addTeam(Team $team)
    {
        $this->getTeams()->add($team);
    }

    /**
     * @param Team $team
     */
    public function removeTeam(Team $team)
    {
        $this->getTeams()->removeElement($team);
    }


}