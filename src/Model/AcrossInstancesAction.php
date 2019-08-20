<?php
/**
 * AcrossInstancesAction
 *
 * PHP version 5
 *
 * @category Class
 * @package  App\Model
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

namespace App\Model;

use JMS\Serializer\Annotation\SerializedName;
use JMS\Serializer\Annotation\Type;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class representing the AcrossInstancesAction model.
 *
 * @package App\Model
 * @author  OpenAPI Generator team
 */
class AcrossInstancesAction 
{
        /**
     * @var App\Model\Instance
     * @SerializedName("source")
     * @Assert\NotNull()
     * @Assert\Type("App\Model\Instance")
     * @Type("App\Model\Instance")
     */
    protected $source;

    /**
     * @var App\Model\Instance
     * @SerializedName("target")
     * @Assert\NotNull()
     * @Assert\Type("App\Model\Instance")
     * @Type("App\Model\Instance")
     */
    protected $target;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->source = isset($data['source']) ? $data['source'] : null;
        $this->target = isset($data['target']) ? $data['target'] : null;
    }

    /**
     * Gets source.
     *
     * @return App\Model\Instance
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Sets source.
     *
     * @param Instance $source
     *
     * @return $this
     */
    public function setSource(Instance $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Gets target.
     *
     * @return App\Model\Instance
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Sets target.
     *
     * @param Instance $target
     *
     * @return $this
     */
    public function setTarget(Instance $target)
    {
        $this->target = $target;

        return $this;
    }
}


