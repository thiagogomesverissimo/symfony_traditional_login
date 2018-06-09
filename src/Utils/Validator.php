<?php

// copied from https://github.com/symfony/demo
namespace App\Utils;

use Symfony\Component\Console\Exception\InvalidArgumentException;

class Validator
{
    public function validateUsername(?string $username): string
    {
        if (empty($username)) {
            throw new InvalidArgumentException('The username can not be empty.');
        }

        if (1 !== preg_match('/^[a-z_]+$/', $username)) {
            throw new InvalidArgumentException('The username must contain only lowercase latin characters and underscores.');
        }

        return $username;
    }

    public function validatePassword(?string $plainPassword): string
    {
        if (empty($plainPassword)) {
            throw new InvalidArgumentException('The password can not be empty.');
        }

        if (mb_strlen(trim($plainPassword)) < 3) {
            throw new InvalidArgumentException('The password must be at least 3 characters long.');
        }

        return $plainPassword;
    }
}
