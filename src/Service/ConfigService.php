<?php


namespace App\Service;


use Doctrine\Common\Annotations\IndexedReader;
use FilesystemIterator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ConfigService
{
    /**
     * @var string
     */
    private $repositoryPath;

    /**
     * @var string
     */
    private $targetRepository;

    /**
     * @var string
     */
    private $configRepository;

    /**
     * @var string
     */
    private $configRepositoryFolder;

    /**
     * ConfigService constructor.
     * @param KernelInterface $kernel
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(KernelInterface $kernel, ParameterBagInterface $parameterBag)
    {
        $this->repositoryPath = $kernel->getProjectDir() . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "transient" . DIRECTORY_SEPARATOR . "repositories";

        $this->targetRepository = $parameterBag->get("TARGET_REPOSITORY");
        $this->configRepository = $parameterBag->get("CONFIG_REPOSITORY");
        $this->configRepositoryFolder = $parameterBag->get("CONFIG_REPOSITORY_FOLDER");
    }

    /**
     * @return array
     */
    public function getConfigFilePaths()
    {
        $targetConfigs = $this->getTargetRepositoryConfigs();
        $configConfigs = $this->getConfigRepositoryConfigs();

        return array_merge($targetConfigs, $configConfigs);
    }

    /**
     * @return string[]
     */
    private function getTargetRepositoryConfigs()
    {
        $repoFolder = $this->repositoryPath . DIRECTORY_SEPARATOR . "target";
        $this->ensureRepositoryExists($repoFolder, $this->targetRepository);

        $agnesFilePath = $repoFolder . DIRECTORY_SEPARATOR . "agnes.yml";
        if (is_file($agnesFilePath)) {
            return [$agnesFilePath];
        }

        return [];
    }

    /**
     * @return array
     */
    private function getConfigRepositoryConfigs()
    {
        $repoFolder = $this->getConfigRepoPath();
        if ($repoFolder === false) {
            return [];
        }

        return glob($repoFolder . DIRECTORY_SEPARATOR . "*.yml");
    }

    /**
     * @param string $repoFolder
     * @param string $repository
     */
    private function ensureRepositoryExists(string $repoFolder, string $repository)
    {
        if (!is_dir($repoFolder)) {
            mkdir($repoFolder, 0777, true);
        }

        // if dir empty, possibly clone failed before. hence we repeat
        if (!(new FilesystemIterator($repoFolder))->valid()) {
            exec("cd $repoFolder && git clone $repository .");
        }

        exec("cd $repoFolder && git pull");
    }

    /**
     * @return string
     */
    public function getConfigRepoPath()
    {
        if (strlen($this->configRepository) === 0) {
            return false;
        }

        $repoFolder = $this->repositoryPath . DIRECTORY_SEPARATOR . "config";
        $this->ensureRepositoryExists($repoFolder, $this->configRepository);

        if (strlen($this->configRepositoryFolder) === 0) {
            return $repoFolder;
        }

        return $repoFolder . DIRECTORY_SEPARATOR . $this->configRepositoryFolder;
    }
}