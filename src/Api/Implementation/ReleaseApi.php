<?php


namespace App\Api\Implementation;


use Agnes\AgnesFactory;
use App\Api\ReleaseApiInterface;
use App\Model\Release;
use Http\Client\Exception;

class ReleaseApi implements ReleaseApiInterface
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
     */
    public function add(Release $release, &$responseCode, array &$responseHeaders)
    {
        $agnesFactory = new AgnesFactory();
        $releaseService = $agnesFactory->createReleaseAction();

        $responseCode = 201;

        $agnesRelease = new \Agnes\Actions\Release($release->getName(), $release->getCommitish(), $release->getDescription());
        $releaseService->release($agnesRelease);
    }
}