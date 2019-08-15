<?php


namespace App\Api;


class ApiServer
{
    /**
     * @var CopySharedApiInterface
     */
    private $copySharedApi;

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
     * @var RollbackApiInterface
     */
    private $rollbackApi;

    /**
     * ApiServer constructor.
     * @param CopySharedApiInterface $copySharedApi
     * @param DeployApiInterface $deployApi
     * @param InstanceApiInterface $instanceApi
     * @param ReleaseApiInterface $releaseApi
     * @param RollbackApiInterface $rollbackApi
     */
    public function __construct(CopySharedApiInterface $copySharedApi, DeployApiInterface $deployApi, InstanceApiInterface $instanceApi, ReleaseApiInterface $releaseApi, RollbackApiInterface $rollbackApi)
    {
        $this->copySharedApi = $copySharedApi;
        $this->deployApi = $deployApi;
        $this->instanceApi = $instanceApi;
        $this->releaseApi = $releaseApi;
        $this->rollbackApi = $rollbackApi;
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \Exception
     */
    public function getApiHandler(string $name)
    {
        switch ($name) {
            case "copyShared":
                return $this->copySharedApi;
            case "deploy":
                return $this->deployApi;
            case "instance":
                return $this->instanceApi;
            case "release":
                return $this->releaseApi;
            case "rollback":
                return $this->rollbackApi;
            default:
                throw new \Exception("handler not implemented");
        }
    }

}