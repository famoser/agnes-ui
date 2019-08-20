<?php


namespace App\Api\Implementation;


use Agnes\Actions\AbstractAction;
use Agnes\Actions\CopySharedAction;
use Agnes\AgnesFactory;
use App\Api\CopySharedApiInterface;
use App\Model\AcrossInstancesAction;
use App\Model\CopyShared;
use Exception;

class CopySharedApi extends AgnesActionBase implements CopySharedApiInterface
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
     * @throws Exception
     */
    public function copyShared(CopyShared $copyShared, &$responseCode, array &$responseHeaders)
    {
        $copyShareds = $this->createExecutablePayloads($copyShared);
        $this->executePayloads($copyShareds);
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
     * @throws Exception
     */
    public function copySharedDryRun(CopyShared $copyShared, &$responseCode, array &$responseHeaders)
    {
        /** @var \Agnes\Actions\CopyShared[] $copyShareds */
        $copyShareds = $this->createExecutablePayloads($copyShared);

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
     * @param CopyShared $configuration
     * @param CopySharedAction $action
     * @return \Agnes\Actions\CopyShared[]
     * @throws Exception
     */
    protected function createPayloads($configuration, $action): array
    {
        return $action->createMany($configuration->getSource(), $configuration->getTarget());
    }

    /**
     * @param AgnesFactory $factory
     * @return AbstractAction
     */
    protected function createAction(AgnesFactory $factory): AbstractAction
    {
        return $factory->createCopySharedAction();
    }
}