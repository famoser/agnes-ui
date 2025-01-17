<?php
/**
 * File
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
 * Class representing the File model.
 *
 * @package App\Model
 * @author  OpenAPI Generator team
 */
class File 
{
        /**
     * @var string
     * @SerializedName("path")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $path;

    /**
     * @var string
     * @SerializedName("content")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $content;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->path = isset($data['path']) ? $data['path'] : null;
        $this->content = isset($data['content']) ? $data['content'] : null;
    }

    /**
     * Gets path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Sets path.
     *
     * @param string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Gets content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets content.
     *
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }
}


