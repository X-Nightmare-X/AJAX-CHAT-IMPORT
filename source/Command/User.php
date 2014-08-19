<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-17 
 */

/**
 * Class Command_User
 */
class Command_User extends Command_AbstractCommand
{
    /**
     * @var AbstractApplication
     */
    private $application;

    /**
     * @var string
     */
    private $command;

    /**
     * @var array
     */
    private $commands = array(
        'add',
        'edit',
        'delete',
        'list'
    );

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Configuration_Path
     */
    private $pathConfiguration;

    /**
     * @param AbstractApplication $application
     */
    public function setApplication(AbstractApplication $application)
    {
        $this->application = $application;
    }

    /**
     * @param Configuration_Path $configuration
     */
    public function setPathConfiguration(Configuration_Path $configuration)
    {
        $this->pathConfiguration = $configuration;
    }

    /**
     * @param \Filesystem $filesystem
     */
    public function setFilesystem(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        switch ($this->command) {
            case 'add':
                $command = $this->application->getUserAddCommand();
                break;
            case 'edit':
                $command = $this->application->getUserEditCommand();
                break;
            case 'delete':
                $command = $this->application->getUserDeleteCommand();
                break;
            case 'list':
                $command = $this->application->getUserListCommand();
                break;
            default:
                throw new Exception(
                    'unsupported command "' . $this->command . '"'
                );
        }

        $command->setArguments($this->arguments);
        try {
            $command->verify();
        } catch (Exception $exception) {
            throw new Exception(
                $this->command . ' ' . implode("\n", $command->getUsage()) . PHP_EOL .
                PHP_EOL .
                $exception->getMessage()
            );
        }
        $command->execute();
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            '[' . implode('|', $this->commands) . ']'
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) < 2) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $command = trim($this->arguments[1]);

        if (!(in_array($command, $this->commands))) {
            throw new Exception(
                'invalid command provided'
            );
        }

        $this->command = $command;
    }
}