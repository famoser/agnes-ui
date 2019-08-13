<?php


namespace App\Service;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepositoryInterface;
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
        $overrideConfigs = $this->getConfigRepositoryConfigs();

        return array_merge($targetConfigs, $overrideConfigs);
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
        $repoFolder = $this->repositoryPath . DIRECTORY_SEPARATOR . "config";
        $this->ensureRepositoryExists($repoFolder, $this->configRepository);

        return glob("*.yml");
    }

    /**
     * @param string $repoFolder
     * @param string $repository
     */
    private function ensureRepositoryExists(string $repoFolder, string $repository)
    {
        if (!is_dir($repoFolder)) {
            mkdir($repoFolder);
            exec("cd $repoFolder && git clone $repository .");
        } else {
            exec("cd $repoFolder && git pull");
        }
    }
}