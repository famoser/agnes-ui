<?php


namespace App\Api\Implementation;


use Agnes\Models\Filter;
use App\Api\InstanceApiInterface;
use App\Model\Instance;
use App\Model\Release;

class InstanceApi extends AgnesBase implements InstanceApiInterface
{

    /**
     * Operation getAll
     *
     * gets all instances
     *
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return Instance[]
     *
     * @throws \Exception
     */
    public function getAll(&$responseCode, array &$responseHeaders)
    {
        $instanceService = $this->getConfiguredAgnesFactory()->getInstanceService();

        $instances = [];
        foreach ($instanceService->getInstancesByFilter(null) as $item) {
            $instance = new Instance();
            $instance->setServer($item->getServerName());
            $instance->setEnvironment($item->getEnvironmentName());
            $instance->setStage($item->getStage());
            $instance->setCurrentReleaseName($item->getCurrentReleaseName());

            $instances[] = $instance;
        }

        return $instances;
    }
}