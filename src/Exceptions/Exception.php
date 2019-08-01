<?php

namespace DansMaCulotte\MondialRelay\Exceptions;

class Exception extends \Exception
{
    /**
     * @param $key
     * @return Exception
     */
    public static function invalidCredentials($key)
    {
        return new self("You must provide a ${key} to authenticate with MondialRelay Web Services");
    }

    /**
     * @param $code
     * @return Exception
     */
    public static function requestError($code)
    {
        return new self("Request returned error code: ${code}");
    }
}
