<?php


namespace App\Api\Implementation;


use Agnes\Actions\Deploy;
use Agnes\Services\Configuration\EditableFile;
use Agnes\Services\ConfigurationService;
use Agnes\Services\Github\ReleaseWithAsset;
use Agnes\Services\GithubService;
use Agnes\Services\InstanceService;
use App\Api\App;
use App\Api\DeployApiInterface;
use App\Model\Deployment;
use App\Model\Instance;
use Http\Client\Exception;

class DeployApi extends AgnesBase implements DeployApiInterface
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
     * @throws Exception
     * @throws \Exception
     */
    public function deploy(Deployment $deployment, &$responseCode, array &$responseHeaders)
    {
        $factory = $this->getConfiguredAgnesFactory();

        /** @var Deploy[] $deploys */
        $deploys = $this->createDeploys($deployment, $factory->getInstanceService(), $factory->getGithubService(), $factory->getConfigurationService());

        $deployAction = $factory->createDeployAction();
        $deployAction->executeMultiple($deploys);
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
     * @throws Exception
     */
    public function deployDryRun(Deployment $deployment, &$responseCode, array &$responseHeaders)
    {
        $factory = $this->getConfiguredAgnesFactory();

        /** @var Deploy[] $deploys */
        $deploys = $this->createDeploys($deployment, $factory->getInstanceService(), $factory->getGithubService(), $factory->getConfigurationService());

        // filter valid executions
        $deployAction = $factory->createDeployAction();
        $deploys = $deployAction->filterCanExecute($deploys);

        $instances = [];
        foreach ($deploys as $deploy) {
            $instances[] = $this->convertAgnesInstanceToInstance($deploy->getTarget());
        }

        return $instances;
    }

    /**
     * @param Deployment $deployment
     * @param InstanceService $instanceService
     * @param GithubService $githubService
     * @param ConfigurationService $configurationService
     * @return Deploy[]
     * @throws Exception
     * @throws \Exception
     */
    private function createDeploys(Deployment $deployment, InstanceService $instanceService, GithubService $githubService, ConfigurationService $configurationService)
    {
        $releaseWithAsset = $this->getRelease($deployment->getRelease(), $githubService);
        if ($releaseWithAsset == null) {
            return [];
        }

        $instances = $instanceService->getInstancesFromInstanceSpecification($deployment->getTarget());
        $requiredFiles = $configurationService->getEditableFiles();

        $deploys = [];
        foreach ($instances as $instance) {
            $allRequiredFilesExist = $this->getFiles($instance, $requiredFiles, $files);
            if ($allRequiredFilesExist) {
                $deploys[] = new Deploy($releaseWithAsset, $instance, $files);
            }
        }

        return $deploys;
    }

    /**
     * @param \Agnes\Models\Instance $instance
     * @param EditableFile[] $requiredFiles
     * @param string[] $files
     * @return string[]|bool
     */
    private function getFiles(\Agnes\Models\Instance $instance, array $requiredFiles, &$files)
    {
        $foundFiles = $this->getConfigService()->loadFilesForInstance($instance);

        foreach ($requiredFiles as $requiredFile) {
            $filePath = $requiredFile->getPath();
            if (isset($foundFiles[$filePath])) {
                $files[$filePath] = $foundFiles[$filePath];
            } else if ($requiredFile->getIsRequired()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param string $releaseName
     * @param GithubService $githubService
     * @return ReleaseWithAsset|null
     * @throws Exception
     */
    private function getRelease(string $releaseName, GithubService $githubService)
    {
        $releases = $githubService->releases();
        foreach ($releases as $release) {
            if ($release->getName() === $releaseName) {
                return $release;
            }
        }

        return null;
    }
}