<?php


namespace App\Api\Implementation;


use Agnes\Actions\AbstractAction;
use Agnes\Actions\ReleaseAction;
use Agnes\AgnesFactory;
use Agnes\Services\GithubService;
use App\Api\ReleaseApiInterface;
use App\Model\Release;
use Http\Client\Exception;

class ReleaseApi extends AgnesActionBase implements ReleaseApiInterface
{
    /**
     * Operation add
     *
     * Add a new release
     *
     * @param Release $release The release to be created (required)
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return void
     * @throws \Exception
     */
    public function add(Release $release, &$responseCode, array &$responseHeaders)
    {
        $releases = $this->createExecutablePayloads($release);
        $this->executePayloads($releases);
    }

    /**
     * Operation getAll
     *
     * Gets all releases
     *
     * @param integer $responseCode The HTTP response code to return
     * @param array $responseHeaders Additional HTTP headers to return with the response ()
     *
     * @return Release[]
     *
     * @throws Exception
     * @throws \Exception
     */
    public function getAll(&$responseCode, array &$responseHeaders)
    {
        $factory = $this->createConfiguredAgnesFactory();

        $githubService = $factory->getGithubService();

        return $this->getReleases($githubService);
    }

    /**
     * @param GithubService $githubService
     * @return Release[]
     * @throws Exception
     */
    private function getReleases(GithubService $githubService)
    {
        $githubReleases = $githubService->releases();

        /** @var Release[] $releases */
        $releases = [];
        foreach ($githubReleases as $githubRelease) {
            $release = new Release();

            $release->setName($githubRelease->getName());
            $release->setCommitish($githubRelease->getCommitish());
            $release->setDescription($githubRelease->getBody());

            $releases[] = $release;
        }

        return $releases;
    }

    /**
     * @param AgnesFactory $factory
     * @return AbstractAction
     */
    protected function createAction(AgnesFactory $factory): AbstractAction
    {
        return $factory->createReleaseAction();
    }

    /**
     * @param Release $configuration
     * @param ReleaseAction $action
     * @return array
     */
    protected function createPayloads($configuration, $action): array
    {
        $release = $action->tryCreate($configuration->getName(), $configuration->getCommitish(), $configuration->getDescription());

        if ($release === null) {
            return [];
        }

        return [$release];
    }
}