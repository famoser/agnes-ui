<?php


namespace App\Api\Implementation;


use Agnes\Actions\Deploy;
use Agnes\Actions\Rollback;
use Agnes\Services\InstanceService;
use App\Api\App;
use App\Api\CopySharedApiInterface;
use App\Model\AcrossInstancesAction;
use App\Model\CopyShared;

class CopySharedApi extends AgnesBase implements CopySharedApiInterface
{
    /**
     * Operation copyShared
     *
     * Copy the shared data from source to target (replicate source on target).
     *
     * @param CopyShared $copyShared The copy shared action (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function copyShared(CopyShared $copyShared, &$responseCode, array &$responseHeaders)
    {
        $factory = $this->getConfiguredAgnesFactory();

        /** @var \Agnes\Actions\CopyShared[] $copyShareds */
        $copyShareds = $this->createCopyShareds($copyShared, $factory->getInstanceService());

        $copySharedAction = $factory->createCopySharedAction();
        $copySharedAction->executeMultiple($copyShareds);
    }

    /**
     * Operation copySharedDryRun
     *
     * Check which instances the copy shared action would affect
     *
     * @param CopyShared $copyShared The copy shared action to dry run (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return AcrossInstancesAction[]
     *
     * @throws \Exception
     */
    public function copySharedDryRun(CopyShared $copyShared, &$responseCode, array &$responseHeaders)
    {
        $factory = $this->getConfiguredAgnesFactory();

        /** @var \Agnes\Actions\CopyShared[] $copyShareds */
        $copyShareds = $this->createCopyShareds($copyShared, $factory->getInstanceService());

        /** @var AcrossInstancesAction[] $acrossInstancesActions */
        $acrossInstancesActions = [];
        foreach ($copyShareds as $copyShared) {

            $source = $this->convertAgnesInstanceToInstance($copyShared->getSource());
            $target = $this->convertAgnesInstanceToInstance($copyShared->getTarget());

            $acrossInstancesAction = new AcrossInstancesAction();
            $acrossInstancesAction->setSource($source);
            $acrossInstancesAction->setTarget($target);

            $acrossInstancesActions[] = $acrossInstancesAction;
        }

        return $acrossInstancesActions;
    }

    /**
     * @param CopyShared $copyShared
     * @param InstanceService $instanceService
     * @return \Agnes\Actions\CopyShared[]|array
     * @throws \Exception
     */
    private function createCopyShareds(CopyShared $copyShared, InstanceService $instanceService)
    {
        $sourceInstances = $instanceService->getInstancesFromInstanceSpecification($copyShared->getSource());
        $targetInstances = $instanceService->getInstancesFromInstanceSpecification($copyShared->getSource());

        /** @var \Agnes\Actions\CopyShared[] $copyShareds */
        $copyShareds = [];
        foreach ($targetInstances as $targetInstance) {
            $matchingInstances = $targetInstance->getSameEnvironmentInstances($sourceInstances);
            if (count($matchingInstances) === 1) {
                $copyShareds[] = new \Agnes\Actions\CopyShared($matchingInstances[0], $targetInstance);
            }
        }

        return $copyShareds;
    }
}