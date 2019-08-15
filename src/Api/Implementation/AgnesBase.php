<?php


namespace App\Api\Implementation;


use Agnes\Actions\Deploy;
use Agnes\AgnesFactory;
use App\Model\Instance;
use App\Service\ConfigService;

class AgnesBase
{
    /**
     * @var ConfigService
     */
    private $configService;

    /**
     * AgnesBase constructor.
     * @param ConfigService $configService
     */
    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }

    /**
     * @return AgnesFactory
     * @throws \Exception
     */
    protected function getConfiguredAgnesFactory()
    {
        $agnesFactory = new AgnesFactory();

        foreach ($this->configService->getConfigFilePaths() as $configFilePath) {
            $agnesFactory->addConfig($configFilePath);
        }

        return $agnesFactory;
    }

    /**
     * @param $agnesInstance
     * @return Instance
     */
    protected function convertAgnesInstanceToInstance(\Agnes\Models\Instance $agnesInstance): Instance
    {
        $instance = new Instance();

        $instance->setServer($agnesInstance->getServerName());
        $instance->setEnvironment($agnesInstance->getEnvironmentName());
        $instance->setStage($agnesInstance->getStage());
        $instance->setCurrentReleaseName($agnesInstance->getCurrentReleaseName());

        return $instance;
    }
}