<?php


namespace App\Api\Implementation;


use Agnes\Actions\AbstractAction;
use Agnes\Actions\RollbackAction;
use Agnes\AgnesFactory;
use Agnes\Services\InstanceService;
use App\Api\App;
use App\Api\RollbackApiInterface;
use App\Model\CopyShared;
use App\Model\Instance;
use App\Model\PendingReleaseInstance;
use App\Model\Rollback;

class RollbackApi extends AgnesActionBase implements RollbackApiInterface
{
    /**
     * Operation rollback
     *
     * Rollback an environment to a previous stage
     *
     * @param Rollback $rollback The Rollback to execute (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function rollback(Rollback $rollback, &$responseCode, array &$responseHeaders)
    {
        $rollbacks = $this->createExecutablePayloads($rollback);
        $this->executePayloads($rollbacks);
    }

    /**
     * Operation rollbackDryRun
     *
     * Check which instances the Rollback would affect
     *
     * @param Rollback $rollback The rollback to dry run (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return PendingReleaseInstance[]
     *
     * @throws \Exception
     */
    public function rollbackDryRun(Rollback $rollback, &$responseCode, array &$responseHeaders)
    {
        /** @var \Agnes\Actions\Rollback[] $rollbacks */
        $rollbacks = $this->createExecutablePayloads($rollback);

        /** @var PendingReleaseInstance[] $pendingReleaseInstances */
        $pendingReleaseInstances = [];
        foreach ($rollbacks as $rollback) {
            $pendingReleaseName = $rollback->getTarget()->getRelease()->getName();
            $instance = $this->convertAgnesInstanceToInstance($rollback->getInstance());

            $pendingReleaseInstance = new PendingReleaseInstance();
            $pendingReleaseInstance->setPendingReleaseName($pendingReleaseName);
            $pendingReleaseInstance->setInstance($instance);
            $pendingReleaseInstances[] = $pendingReleaseInstance;
        }

        return $pendingReleaseInstances;
    }

    /**
     * @param AgnesFactory $factory
     * @return AbstractAction
     */
    protected function createAction(AgnesFactory $factory): AbstractAction
    {
        return $factory->createRollbackAction();
    }

    /**
     * @param Rollback $configuration
     * @param RollbackAction $action
     * @return array
     * @throws \Exception
     */
    protected function createPayloads($configuration, $action): array
    {
        /**
         * @param $argument
         * @return string|null
         */
        $nonEmptyStringOrNull = function ($argument) {
            if ($argument === null || !is_string($argument) || strlen(trim($argument)) === 0) {
                return null;
            }

            return $argument;
        };

        $rollbackTo = $nonEmptyStringOrNull($configuration->getRollbackTo());
        $rollbackFrom = $nonEmptyStringOrNull($configuration->getRollbackFrom());

        return $action->createMany($configuration->getTarget(), $rollbackTo, $rollbackFrom);
    }
}