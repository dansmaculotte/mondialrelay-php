<?php

namespace DansMaCulotte\MondialRelay\Exceptions;

use Exception;

class CoordinateFormatException extends Exception
{
    public static function digitError(): self
    {
        return new self('2 digits max are expected for coordinate floor value');
    }

    public static function formatError():self
    {
        return new self('Coordinates does not follow expected pattern');
    }
}
