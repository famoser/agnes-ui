<?php


namespace App\Api\Implementation;


use Agnes\Services\GithubService;
use App\Api\App;
use App\Api\ReleaseApiInterface;
use App\Model\Release;
use Http\Client\Exception;

class ReleaseApi extends AgnesBase implements ReleaseApiInterface
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
        $agnesFactory = $this->getConfiguredAgnesFactory();
        $releaseService = $agnesFactory->createReleaseAction();

        $responseCode = 201;

        $agnesRelease = new \Agnes\Actions\Release($release->getName(), $release->getCommitish(), $release->getDescription());
        $releaseService->execute($agnesRelease);
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
        $factory = $this->getConfiguredAgnesFactory();

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
}