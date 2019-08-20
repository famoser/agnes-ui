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

abstract class AgnesActionBase extends AgnesBase
{
    /**
     * @var AbstractAction
     */
    private $action;

    /**
     * @return AbstractAction
     * @throws \Exception
     */
    protected function getOrCreateAction()
    {
        if ($this->action === null) {
            $factory = $this->createConfiguredAgnesFactory();
            $this->action = $this->createAction($factory);
        }

        return $this->action;
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

    /**
     * @param AbstractAction $copySharedAction
     * @param AbstractPayload[] $payloads
     * @return AbstractPayload[]
     * @throws \Exception
     */
    protected function filterExecutablePayloads(AbstractAction $copySharedAction, array $payloads): array
    {
        $result = [];
        foreach ($payloads as $payload) {
            if ($copySharedAction->canExecute($payload)) {
                $result[] = $payload;
            }
        }

        return $result;
    }

    /**
     * @param AbstractAction $copySharedAction
     * @param AbstractPayload[] $payloads
     * @throws \Exception
     */
    protected function executePayloads(array $payloads)
    {
        $action = $this->getOrCreateAction();

        $outputInterface = new NullOutput();

        foreach ($payloads as $payload) {
            $action->execute($payload, $outputInterface);
        }
    }

    /**
     * @param mixed $configuration
     * @return \Agnes\Actions\CopyShared[]|array
     * @throws \Exception
     */
    protected function createExecutablePayloads($configuration)
    {
        $action = $this->getOrCreateAction();

        $payloads = $this->createPayloads($configuration, $action);

        return $this->filterExecutablePayloads($action, $payloads);
    }

    /**
     * @param AgnesFactory $factory
     * @return AbstractAction
     */
    protected abstract function createAction(AgnesFactory $factory): AbstractAction;

    /**
     * @param mixed $configuration
     * @param AbstractAction $action
     * @return array
     */
    protected abstract function createPayloads($configuration, $action): array;
}