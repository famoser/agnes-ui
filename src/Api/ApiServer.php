<?php


namespace App\Api;


use App\Api\Implementation\AgnesBase;

class ApiServer
{
    /**
     * @var DeployApiInterface
     */
    private $deployApi;

    /**
     * @var InstanceApiInterface
     */
    private $instanceApi;

    /**
     * @var ReleaseApiInterface
     */
    private $releaseApi;

    /**
     * ApiServer constructor.
     * @param DeployApiInterface $deployApi
     * @param InstanceApiInterface $instanceApi
     * @param ReleaseApiInterface $releaseApi
     */
    public function __construct(DeployApiInterface $deployApi, InstanceApiInterface $instanceApi, ReleaseApiInterface $releaseApi)
    {
        $this->deployApi = $deployApi;
        $this->instanceApi = $instanceApi;
        $this->releaseApi = $releaseApi;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function getApiHandler(string $name)
    {
        switch ($name) {
            case "deploy":
                return $this->deployApi;
            case "instance":
                return $this->instanceApi;
            case "release":
                return $this->releaseApi;
            default:
                throw new \Exception("handler not implemented");
        }
    }

}