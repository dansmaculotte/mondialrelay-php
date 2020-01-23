<?php

namespace DansMaCulotte\MondialRelay\Exceptions;

class Exception extends \RuntimeException
{
    public static function invalidCredentials(string $key): Exception
    {
        return new self("You must provide a ${key} to authenticate with MondialRelay Web Services");
    }

    public static function requestError(string $code): Exception
    {
        return new self("Request returned error code: ${code}");
    }

    public static function noPickupPoint(string $code): Exception
    {
        return new self("No pickup point found with code: ${code}");
    }
}
