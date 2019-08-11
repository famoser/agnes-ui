<?php


namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

trait TargetTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $serverName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $environmentName;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $stageName;

    /**
     * @return string
     */
    public function getServerName()
    {
        return $this->serverName;
    }

    /**
     * @param string $serverName
     */
    public function setServerName($serverName)
    {
        $this->serverName = $serverName;
    }

    /**
     * @return string
     */
    public function getEnvironmentName()
    {
        return $this->environmentName;
    }

    /**
     * @param string $environmentName
     */
    public function setEnvironmentName($environmentName)
    {
        $this->environmentName = $environmentName;
    }

    /**
     * @return string
     */
    public function getStageName()
    {
        return $this->stageName;
    }

    /**
     * @param string $stageName
     */
    public function setStageName($stageName)
    {
        $this->stageName = $stageName;
    }
}