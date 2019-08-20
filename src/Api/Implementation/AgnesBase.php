<?php


namespace App\Api\Implementation;


use Agnes\AgnesFactory;
use App\Service\ConfigService;
use Exception;

abstract class AgnesBase
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
     * @return ConfigService
     */
    protected function getConfigService(): ConfigService
    {
        return $this->configService;
    }

    /**
     * @return AgnesFactory
     * @throws Exception
     */
    protected function createConfiguredAgnesFactory()
    {
        $agnesFactory = new AgnesFactory();

        foreach ($this->configService->getConfigFilePaths() as $configFilePath) {
            $agnesFactory->addConfig($configFilePath);
        }

        return $agnesFactory;
    }
}