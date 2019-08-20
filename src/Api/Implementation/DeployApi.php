<?php


namespace App\Api\Implementation;


use Agnes\Actions\AbstractAction;
use Agnes\Actions\Deploy;
use Agnes\Actions\DeployAction;
use Agnes\AgnesFactory;
use Agnes\Services\Configuration\File;
use Agnes\Services\ConfigurationService;
use Agnes\Services\Github\ReleaseWithAsset;
use Agnes\Services\GithubService;
use Agnes\Services\InstanceService;
use App\Api\DeployApiInterface;
use App\Model\CopyShared;
use App\Model\Deployment;
use App\Model\Instance;
use Http\Client\Exception;

class DeployApi extends AgnesActionBase implements DeployApiInterface
{
    /**
     * Operation deploy
     *
     * Deploy to environments
     *
     * @param Deployment $deployment The deployment to start (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return void
     *
     * @throws \Exception
     */
    public function deploy(Deployment $deployment, &$responseCode, array &$responseHeaders)
    {
        $deploys = $this->createExecutablePayloads($deployment);
        $this->executePayloads($deploys);
    }

    /**
     * Operation deployDryRun
     *
     * Check which instances the deploy would affect
     *
     * @param Deployment $deployment The deployment to dry run (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return Instance[]
     *
     * @throws \Exception
     */
    public function deployDryRun(Deployment $deployment, &$responseCode, array &$responseHeaders)
    {
        /** @var Deploy[] $deploys */
        $deploys = $this->createExecutablePayloads($deployment);

        $instances = [];
        foreach ($deploys as $deploy) {
            $instances[] = $this->convertAgnesInstanceToInstance($deploy->getTarget());
        }

        return $instances;
    }

    /**
     * @param Deployment $configuration
     * @param DeployAction $action
     * @return Deploy[]
     * @throws Exception
     */
    protected function createPayloads($configuration, $action): array
    {
        $configFolder = $this->getConfigService()->getConfigRepoPath();
        if ($configFolder === false) {
            $configFolder = null;
        }

        return $action->createMany($configuration->getRelease(), $configuration->getTarget(), $configFolder, false);
    }

    /**
     * @param AgnesFactory $factory
     * @return AbstractAction
     */
    protected function createAction(AgnesFactory $factory): AbstractAction
    {
        return $factory->createDeployAction();
    }
}