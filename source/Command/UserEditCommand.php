<?php
/**
 * @author stev leibelt <artodeto@bazzline.net>
 * @since 2014-08-13 
 */

/**
 * Class UserEditCommand
 */
class UserEditCommand extends AbstractUserCommand
{
    /**
     * @var array
     */
    private $inputChannels;

    /**
     * @var string
     */
    private $inputName;

    /**
     * @var string
     */
    private $inputPassword;

    /**
     * @var string
     */
    private $inputRole;

    /**
     * @var int
     */
    private $inputId;

    /**
     * @throws Exception
     */
    public function execute()
    {
        reset($this->users);

        $lines = $this->file->read();
        $content = array();
        $contentAfterCurrentUser = array();
        $string = new String();

        $foundCurrentUserContent = false;
        $linePrefixToSearchFor = '$users[' . $this->inputId . '][';

        foreach ($lines as $line) {
            if ($string->startsWith($line, $linePrefixToSearchFor)) {
                $foundCurrentUserContent = true;
            } else {
                if ($foundCurrentUserContent) {
                    $contentAfterCurrentUser[] = $line;
                } else {
                    $content[] = $line;
                }
            }
        }

        $content[] = '$users[' . $this->inputId . '][\'userRole\'] = ' . $this->roles[$this->inputRole] . ';';
        $content[] = '$users[' . $this->inputId . '][\'userName\'] = \'' . $this->inputName . '\';';
        $content[] = '$users[' . $this->inputId . '][\'password\'] = \'' . $this->inputPassword . '\';';
        $content[] = '$users[' . $this->inputId . '][\'channels\'] = array(' . implode(',', $this->inputChannels) . ');';

        foreach ($contentAfterCurrentUser as $line) {
            $content[] = $line;
        }

        $this->file->write($content);
    }

    /**
     * @return array
     */
    public function getUsage()
    {
        return array(
            ' "id" "login name" "password" "role" "channels"',
            '   available channels: ' . implode(',', array_keys($this->channels)),
            '   available roles: ' . implode(',', array_keys($this->roles)),
            '   available users: ' . implode(',', array_keys($this->users))
        );
    }

    /**
     * @throws Exception
     */
    public function verify()
    {
        if (count($this->arguments) !== 7) {
            throw new Exception(
                'invalid number of arguments provided'
            );
        }

        $channels = explode(',', trim($this->arguments[6]));
        $name = trim($this->arguments[3]);
        $password = trim($this->arguments[4]);
        $role = trim($this->arguments[5]);
        $userId = (int) $this->arguments[2];

        if (strlen($name) < 1) {
            throw new Exception(
                'invalid name "' . $name . '" provided'
            );
        }

        if (strlen($role) < 1) {
            throw new Exception(
                'invalid name "' . $role . '" provided'
            );
        } else {
            if (!isset($this->roles[$role])) {
                throw new Exception(
                    'invalid role "' . $role . '" provided'
                );
            }
        }

        if (strlen($password) < 1) {
            throw new Exception(
                'invalid name "' . $password . '" provided'
            );
        }

        if (empty($channels)) {
            throw new Exception(
                'no channels provided'
            );
        }

        foreach ($channels as $channel) {
            if (!isset($this->channels[$channel])) {
                throw new Exception(
                    'invalid channel "' . $channel . '" provided'
                );
            }
        }

        if (($userId === 0)) {
            throw new Exception(
                'no id provided'
            );
        } else if (!isset($this->users[$userId])) {
            throw new Exception(
                'invalid id provided'
            );
        }

        $this->inputChannels = $channels;
        $this->inputName = $name;
        $this->inputPassword = $password;
        $this->inputRole = $role;
        $this->inputId = $userId;
    }
}