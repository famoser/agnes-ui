<?php


namespace App\Entity;


use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TargetTrait;

/**
 * Class File
 * @package App\Entity
 */
class File
{
    use IdTrait;
    use TargetTrait;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }
}