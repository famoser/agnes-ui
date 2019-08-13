<?php


namespace App\Entity;


use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TargetTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 */
class Task
{
    use IdTrait;
    use TargetTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $log;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    private $executionStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $executionTime;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getLog(): string
    {
        return $this->log;
    }

    /**
     * @param string $log
     */
    public function setLog(string $log): void
    {
        $this->log = $log;
    }

    /**
     * @return \DateTime
     */
    public function getExecutionStart(): \DateTime
    {
        return $this->executionStart;
    }

    /**
     * @param \DateTime $executionStart
     */
    public function setExecutionStart(\DateTime $executionStart): void
    {
        $this->executionStart = $executionStart;
    }

    /**
     * @return \DateTime
     */
    public function getExecutionTime(): \DateTime
    {
        return $this->executionTime;
    }

    /**
     * @param \DateTime $executionTime
     */
    public function setExecutionTime(\DateTime $executionTime): void
    {
        $this->executionTime = $executionTime;
    }
}