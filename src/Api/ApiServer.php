<?php


namespace App\Api;


class ApiServer
{
    /**
     * @var ReleaseApiInterface
     */
    private $releaseApi;

    /**
     * ApiServer constructor.
     * @param ReleaseApiInterface $releaseApi
     */
    public function __construct(ReleaseApiInterface $releaseApi)
    {
        $this->releaseApi = $releaseApi;
    }

    /**
     * @param string $name
     * @return ReleaseApiInterface
     * @throws \Exception
     */
    public function getApiHandler(string $name)
    {
        switch ($name) {
            case "release":
                return $this->releaseApi;
            default:
                throw new \Exception("handler not implemented");
        }
    }

}