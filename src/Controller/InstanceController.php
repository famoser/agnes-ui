<?php

/**
 * InstanceController
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

use App\Api\InstanceApiInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * InstanceController Class Doc Comment
 *
 * @category Class
 * @package  App\Controller
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
class InstanceController extends Controller
{

    /**
     * Operation getAll
     *
     * gets all instances
     *
     * @param Request $request The Symfony request to handle.
     * @return Response The Symfony response.
     */
    public function getAllAction(Request $request)
    {
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

        // Use the default value if no value was provided

        // Validate the input values


        try {
            $handler = $this->getApiHandler();

            
            // Make the call to the business logic
            $responseCode = 200;
            $responseHeaders = [];
            $result = $handler->getAll($responseCode, $responseHeaders);

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
     * @return InstanceApiInterface
     * @throws Exception
     * @throws Exception
     */
    public function getApiHandler()
    {
        return $this->apiServer->getApiHandler('instance');
    }
}
