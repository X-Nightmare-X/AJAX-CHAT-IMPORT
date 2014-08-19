<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-14 
 */

try {
    require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

    $application = new Application_Cli();
    //@todo verify if is installed and up to date
    $command = $application->getBackupCommand();
    $command->setArguments($argv);
    try {
        $command->verify();
    } catch (Exception $exception) {
        throw new Exception('Usage:' . PHP_EOL . basename(__FILE__) . ' ' . implode("\n", $command->getUsage()));
    }
    $command->execute();

    foreach ($command->getOutput()->toArray() as $line) {
        echo $line . PHP_EOL;
    }
} catch (Exception $exception) {
    echo 'error occurred' . PHP_EOL;
    echo '----------------' . PHP_EOL;
    echo 'Usage:' . PHP_EOL . basename(__FILE__) . ' ' . $exception->getMessage() . PHP_EOL;
    exit(1);
}