<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_Validate
 */
class Command_Validate extends Command_AbstractCommand
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Configuration_Path
     */
    private $pathConfiguration;

    /**
     * @param Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param Configuration_Path $configuration
     */
    public function setPathConfiguration(Configuration_Path $configuration)
    {
        $this->pathConfiguration = $configuration;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        //@todo move into Command_Validate_Local_Files
        $pathToLibDirectory = $this->pathConfiguration->getChatPath() . DIRECTORY_SEPARATOR . 'lib';

        if (!$this->filesystem->isDirectory($pathToLibDirectory)) {
            throw new Exception(
                'directory "lib" is missing, installation needed'
            );
        }

        $pathToDataDirectory = $pathToLibDirectory . DIRECTORY_SEPARATOR . 'data';

        if (!$this->filesystem->isDirectory($pathToDataDirectory)) {
            throw new Exception(
                'directory "data" is missing, installation needed'
            );
        }

        $identifierToPublicPath = array(
            'channels' => $this->pathConfiguration->getChatChannelsFilePath(),
            'configuration'  => $this->pathConfiguration->getChatConfigurationFilePath(),
            'users' => $this->pathConfiguration->getChatUsersFilePath(),
            'version' => $this->pathConfiguration->getChatVersionFilePath()
        );

        foreach ($identifierToPublicPath as $identifier => $path) {
            if (!$this->filesystem->isFile($path)) {
                throw new Exception(
                    'file "' . $identifier . '" is missing, installation needed'
                );
            }
        }

        //@todo move into Command_Validate_Version
        $exampleVersion = require_once $this->pathConfiguration->getExampleVersionFilePath();
        $chatVersion = require_once $this->pathConfiguration->getChatVersionFilePath();

        if ($exampleVersion !== $chatVersion) {
            throw new Exception(
                'current version "' . $chatVersion . '" and code version "' . $exampleVersion . '" differs, update needed'
            );
        }

        echo 'installation is valid.' . PHP_EOL;
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array();
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
    }
}