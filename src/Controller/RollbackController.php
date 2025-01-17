<?php

/**
 * RollbackController
 * PHP version 5
 *
 * @category Class
 * @package  App\Controller
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

namespace App\Controller;

use App\Api\RollbackApiInterface;
use Exception;
use JMS\Serializer\Exception\RuntimeException as SerializerRuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RollbackController Class Doc Comment
 *
 * @category Class
 * @package  App\Controller
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
class RollbackController extends Controller
{

    /**
     * Operation rollback
     *
     * Rollback an environment to a previous stage
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     */
    public function rollbackAction(Request $request)
    {
        // Make sure that the client is providing something that we can consume
        $consumes = ['application/json'];
        $inputFormat = $request->headers->has('Content-Type')?$request->headers->get('Content-Type'):$consumes[0];
        if (!in_array($inputFormat, $consumes)) {
            // We can't consume the content that the client is sending us
            return new Response('', 415);
        }

        // Handle authentication

        // Read out all input parameter values into variables
        $rollback = $request->getContent();

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $rollback = $this->deserialize($rollback, 'App\Model\Rollback', $inputFormat);
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("App\Model\Rollback");
        $asserts[] = new Assert\Valid();
        $response = $this->validate($rollback, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            
            // Make the call to the business logic
            $responseCode = 204;
            $responseHeaders = [];
            $result = $handler->rollback($rollback, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'Created';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 201:
                    $message = 'Created';
                    break;
            }

            return new Response(
                '',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Operation rollbackDryRun
     *
     * Check which instances the Rollback would affect
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     */
    public function rollbackDryRunAction(Request $request)
    {
        // Make sure that the client is providing something that we can consume
        $consumes = ['application/json'];
        $inputFormat = $request->headers->has('Content-Type')?$request->headers->get('Content-Type'):$consumes[0];
        if (!in_array($inputFormat, $consumes)) {
            // We can't consume the content that the client is sending us
            return new Response('', 415);
        }

        // Figure out what data format to return to the client
        $produces = ['application/json'];
        // Figure out what the client accepts
        $clientAccepts = $request->headers->has('Accept')?$request->headers->get('Accept'):'*/*';
        $responseFormat = $this->getOutputFormat($clientAccepts, $produces);
        if ($responseFormat === null) {
            return new Response('', 406);
        }

        // Handle authentication

        // Read out all input parameter values into variables
        $rollback = $request->getContent();

        // Use the default value if no value was provided

        // Deserialize the input values that needs it
        try {
            $rollback = $this->deserialize($rollback, 'App\Model\Rollback', $inputFormat);
        } catch (SerializerRuntimeException $exception) {
            return $this->createBadRequestResponse($exception->getMessage());
        }

        // Validate the input values
        $asserts = [];
        $asserts[] = new Assert\NotNull();
        $asserts[] = new Assert\Type("App\Model\Rollback");
        $asserts[] = new Assert\Valid();
        $response = $this->validate($rollback, $asserts);
        if ($response instanceof Response) {
            return $response;
        }


        try {
            $handler = $this->getApiHandler();

            
            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->rollbackDryRun($rollback, $responseCode, $responseHeaders);

            // Find default response message
            $message = 'OK';

            // Find a more specific message, if available
            switch ($responseCode) {
                case 200:
                    $message = 'OK';
                    break;
            }

            return new Response(
                $result !== null ?$this->serialize($result, $responseFormat):'',
                $responseCode,
                array_merge(
                    $responseHeaders,
                    [
                        'Content-Type' => $responseFormat,
                        'X-OpenAPI-Message' => $message
                    ]
                )
            );
        } catch (Exception $fallthrough) {
            return $this->createErrorResponse(new HttpException(500, 'An unsuspected error occurred.', $fallthrough));
        }
    }

    /**
     * Returns the handler for this API controller.
     * @return RollbackApiInterface
     * @throws Exception
     * @throws Exception
     */
    public function getApiHandler()
    {
        return $this->apiServer->getApiHandler('rollback');
    }
}
