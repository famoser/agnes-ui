<?php


namespace App\Service;


use Agnes\Models\Instance;
use Doctrine\Common\Annotations\IndexedReader;
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
     * ConfigService constructor.
     * @param KernelInterface $kernel
     * @param ParameterBagInterface $parameterBag
     */
    public function __construct(KernelInterface $kernel, ParameterBagInterface $parameterBag)
    {
        $this->repositoryPath = $kernel->getProjectDir() . DIRECTORY_SEPARATOR . "var" . DIRECTORY_SEPARATOR . "transient" . DIRECTORY_SEPARATOR . "repositories";

        $this->targetRepository = $parameterBag->get("TARGET_REPOSITORY");
        $this->configRepository = $parameterBag->get("CONFIG_REPOSITORY");
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
     * @param Instance $instance
     * @return string[]
     */
    public function loadFilesForInstance(Instance $instance)
    {
        $repoFolder = $this->getConfigRepoPath();
        if ($repoFolder === false) {
            return [];
        }

        $instanceFolder = $repoFolder . DIRECTORY_SEPARATOR .
            $instance->getServerName() . DIRECTORY_SEPARATOR .
            $instance->getEnvironmentName() . DIRECTORY_SEPARATOR .
            $instance->getStage();

        $filePaths = $this->getFilesRecursively($instanceFolder);

        $result = [];
        $instanceFolderPrefixLength = strlen($instanceFolder) + 1;
        foreach ($filePaths as $filePath) {
            $content = file_get_contents($filePath);
            $key = substr($filePath, $instanceFolderPrefixLength);

            $result[$key] = $content;
        }

        return $result;
    }

    /**
     * @param string $folder
     * @return string[]
     */
    private function getFilesRecursively(string $folder)
    {
        $directoryElements = scandir($folder);

        $result = [];
        foreach ($directoryElements as $key => $value) {
            $path = realpath($folder . DIRECTORY_SEPARATOR . $value);
            if (!is_dir($path)) {
                $result[] = $path;
            } else if ($value != "." && $value != "..") {
                $result = array_merge($result, $this->getFilesRecursively($path));
            }
        }

        return $result;
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
        if (!(new \FilesystemIterator($repoFolder))->valid()) {
            exec("cd $repoFolder && git clone $repository .");
        }

        exec("cd $repoFolder && git pull");
    }

    /**
     * @return string
     */
    private function getConfigRepoPath()
    {
        if (strlen($this->configRepository) === 0) {
            return false;
        }

        $repoFolder = $this->repositoryPath . DIRECTORY_SEPARATOR . "config";
        $this->ensureRepositoryExists($repoFolder, $this->configRepository);

        return $repoFolder;
    }
}