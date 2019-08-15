<?php


namespace App\Api\Implementation;


use Agnes\Services\InstanceService;
use App\Api\App;
use App\Api\RollbackApiInterface;
use App\Model\CopyShared;
use App\Model\Instance;
use App\Model\PendingReleaseInstance;
use App\Model\Rollback;

class RollbackApi extends AgnesBase implements RollbackApiInterface
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
        $factory = $this->getConfiguredAgnesFactory();

        /** @var \Agnes\Actions\Rollback[] $rollbacks */
        $rollbacks = $this->createRollbacks($rollback, $factory->getInstanceService());

        $rollbackAction = $factory->createRollbackAction();
        $rollbackAction->executeMultiple($rollbacks);
    }

    /**
     * @param Rollback $rollback
     * @param InstanceService $instanceService
     * @return Rollback[]
     * @throws \Exception
     */
    private function createRollbacks(Rollback $rollback, InstanceService $instanceService): array
    {
        $targets = $instanceService->getInstancesFromInstanceSpecification($rollback->getTarget());

        /** @var Rollback[] $rollbacks */
        $rollbacks = [];
        foreach ($targets as $target) {
            $rollbackInstallation = $target->getRollbackTarget($rollback->getRollbackTo(), $rollback->getRollbackFrom());
            if ($rollbackInstallation !== null) {
                $rollbacks[] = new \Agnes\Actions\Rollback($target, $rollbackInstallation);
            }
        }

        return $rollbacks;
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
        $factory = $this->getConfiguredAgnesFactory();

        /** @var \Agnes\Actions\Rollback[] $rollbacks */
        $rollbacks = $this->createRollbacks($rollback, $factory->getInstanceService());

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
}