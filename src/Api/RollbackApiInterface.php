<?php
/**
 * RollbackApiInterface
 * PHP version 5
 *
 * @category Class
 * @package  App
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */

/**
 * Agnes UI
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.0.0
 * 
 * Generated by: https://github.com/openapitools/openapi-generator.git
 *
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */

namespace App\Api;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Model\PendingReleaseInstance;
use App\Model\Rollback;

/**
 * RollbackApiInterface Interface Doc Comment
 *
 * @category Interface
 * @package  App\Api
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
interface RollbackApiInterface
{

    /**
     * Operation rollback
     *
     * Rollback an environment to a previous stage
     *
     * @param  App\Model\Rollback $rollback  The Rollback to execute (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return void
     *
     */
    public function rollback(Rollback $rollback, &$responseCode, array &$responseHeaders);

    /**
     * Operation rollbackDryRun
     *
     * Check which instances the Rollback would affect
     *
     * @param  App\Model\Rollback $rollback  The rollback to dry run (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return App\Model\PendingReleaseInstance[]
     *
     */
    public function rollbackDryRun(Rollback $rollback, &$responseCode, array &$responseHeaders);
}
