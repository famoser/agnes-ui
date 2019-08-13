<?php


namespace App\Api\Implementation;


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
     * @throws Exception
     * @throws \Exception
     */
    public function add(Release $release, &$responseCode, array &$responseHeaders)
    {
        $agnesFactory = $this->getConfiguredAgnesFactory();
        $releaseService = $agnesFactory->createReleaseAction();

        $responseCode = 201;

        $agnesRelease = new \Agnes\Actions\Release($release->getName(), $release->getCommitish(), $release->getDescription());
        $releaseService->release($agnesRelease);
    }
}