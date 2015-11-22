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
 * @ORM\Table(name="players", uniqueConstraints={
 *  @ORM\UniqueConstraint(name="user_idx", columns={"user_id"})
 * })
 * @ORM\HasLifecycleCallbacks
 */
class Player implements \JsonSerializable
{
    use BasicEntityTrait;
    use ImageableTrait;
    use ActivableTrait;
    use TimestampableEntity;

    /**
     * @var \Zantolov\AppBundle\Entity\User
     * @ORM\OneToOne(targetEntity="Zantolov\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    private $team;


    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14, nullable=true)
     */
    private $score;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    private $alias;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $level;

    /**
     * @var float
     * @ORM\Column(type="decimal", precision=18, scale=14, nullable=true)
     */
    private $calories;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=true)
     */
    private $steps;

    /**
     * @var boolean
     * @ORM\Column(type="boolean")
     */
    private $isLive = true;


    public function __construct()
    {
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
     * @return Team
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * @param Team $team
     */
    public function setTeam($team)
    {
        $this->team = $team;
    }

    /**
     * @return boolean
     */
    public function isLive()
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


    function jsonSerialize()
    {
        return [
            'id'     => $this->getId(),
            'alias'  => $this->getAlias(),
            'level'  => $this->getLevel(),
            'team'   => $this->getTeam()->getId(),
            'isLive' => $this->isLive(),
            'image' => $this->getImage()->getImageName(),
        ];
    }

}