<?php


namespace App\Api\Implementation;


use Agnes\Actions\AbstractAction;
use Agnes\Actions\AbstractPayload;
use Agnes\Actions\CopySharedAction;
use Agnes\Actions\Deploy;
use Agnes\AgnesFactory;
use App\Model\CopyShared;
use App\Model\Instance;
use App\Service\ConfigService;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\NullOutput;

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
     * @throws \Exception
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