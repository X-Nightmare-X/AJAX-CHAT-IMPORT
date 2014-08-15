<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class Command_AbstractCommandCommand
 */
abstract class Command_AbstractCommandCommand implements Command_CommandInterface
{
    /**
     * @var array
     */
    protected $arguments;

    /**
     * @param array $arguments
     */
    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;
    }
}