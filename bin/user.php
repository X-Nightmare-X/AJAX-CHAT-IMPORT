<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-12
 */

$isNotCalledFromCommandLineInterface = (PHP_SAPI !== 'cli');

try {
    if ($isNotCalledFromCommandLineInterface) {
        throw new Exception(
            'command line script only '
        );
    }

    $validCommands = array(
        'add'       => 'UserAddCommand',
        'edit'      => 'UserEditCommand',
        'delete'    => 'UserDeleteCommand',
        'list'      => 'UserListCommand'
    );
    $usage = 'Usage: ' . PHP_EOL .
        basename(__FILE__) . ' [' . implode('|', array_keys($validCommands)) . ']' . PHP_EOL;

    if ($argc < 2) {
        echo $usage;
        exit(1);
    }

    $currentCommand = trim($argv[1]);

    if (!(isset($validCommands[$currentCommand]))) {
        echo $usage;
        exit(1);
    }

    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';
    require_once 'install.php';

    $pathToChannelsPhp = $configuration['path_to_public_channels'];
    $pathToUsersPhp = $configuration['path_to_public_users'];

    require_once $pathToChannelsPhp;
    require_once $pathToUsersPhp;

    $commandClass = $validCommands[$currentCommand];
    $fileToUsers = new File($pathToUsersPhp);

    /** @var UserCommandInterface $command */
    $command = new $commandClass();

    $command->setArguments($argv);
    $command->setChannels($channels);
    $command->setRoles($roles);
    $command->setUsers($users);
    $command->setUserFile($fileToUsers);

    try {
        $command->verify();
    } catch (Exception $exception) {
        throw new Exception($currentCommand . ' ' . implode("\n", $command->getUsage()));
    }
    $command->execute();
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo $usage . PHP_EOL;
    echo $exception->getMessage() . PHP_EOL;
    exit(1);
}