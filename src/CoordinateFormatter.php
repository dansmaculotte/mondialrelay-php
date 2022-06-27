<?php

namespace DansMaCulotte\MondialRelay;

use DansMaCulotte\MondialRelay\Exceptions\CoordinateFormatException;

class CoordinateFormatter
{
    public static function format(string $latlng): string
    {
        $latlngData = explode('.', str_replace(',', '.', $latlng));
        if (count($latlngData) !== 2) {
            throw CoordinateFormatException::formatError();
        }
        if ($latlng[0] === '-') {
            if (strlen($latlngData[0]) > 3) {
                throw CoordinateFormatException::digitError();
            }
            return self::checkResult(
                substr($latlngData[0], 0, 3) . '.' . str_pad(substr($latlngData[1], 0, 7), 6, '0', STR_PAD_RIGHT)
            );
        }
        if (strlen($latlngData[0]) > 2) {
            throw CoordinateFormatException::digitError();
        }
        return self::checkResult(
            substr($latlngData[0], 0, 2) . '.' .  str_pad(substr($latlngData[1], 0, 7), 6, '0', STR_PAD_RIGHT)
        );
    }

    private static function checkResult(string $result): string
    {
        /*
            Documentation state that latitude and longitude should match
            ^-?[0-9]{2}\.[0-9]{7}$. Yet, using only one digit in integer part
            works for longitudes and using only 6 digit as decimal part also
            works.
         */
        if (preg_match('/^-?[0-9]{1,2}\.[0-9]{6,7}$/', $result)) {
            return $result;
        }
        throw CoordinateFormatException::formatError();
    }
}
