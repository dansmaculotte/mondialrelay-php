<?php

namespace DansMaCulotte\MondialRelay\Tests;

use DansMaCulotte\MondialRelay\CoordinateFormatter;
use PHPUnit\Framework\TestCase;

/*
    According to documentation, the format for latitude and longitude parameters
    follow this pattern: ^-?[0-9]{1,2}\.[0-9]{6,7}$ (11 chars max)
    I guess they do not plan to have points having longitudes with abs value greater than 90!
 */
class CoordinateFormatterTest extends TestCase
{
    /** @test */
    public function should_keep_exact_number_of_digit()
    {
        $val = '12.12345678910';
        $this->assertEquals('12.1234567', CoordinateFormatter::format($val));
    }

    /** @test */
    public function should_handle_values_with_coma()
    {
        $val = '12,12345678910';
        $this->assertEquals('12.1234567', CoordinateFormatter::format($val));
    }

    /** @test */
    public function should_handle_negative_values()
    {
        $val = '-12.12345678910';
        $this->assertEquals('-12.1234567', CoordinateFormatter::format($val));
    }

    /** @test */
    public function should_throw_an_exception_if_floor_value_has_more_than_2_digits()
    {
        $this->expectExceptionMessage('2 digits max are expected for coordinate floor value');
        CoordinateFormatter::format('112.12345678910');
    }

    /** @test */
    public function should_throw_an_exception_if_negative_floor_value_has_more_than_3_digits()
    {
        $this->expectExceptionMessage('2 digits max are expected for coordinate floor value');
        CoordinateFormatter::format('-112.12345678910');
    }

    /** @test */
    public function should_throw_an_exception_if_final_value_does_not_match_expected_pattern()
    {
        $this->expectExceptionMessage('Coordinates does not follow expected pattern');
        CoordinateFormatter::format('ab.def');
    }

    /** @test */
    public function should_throw_an_exception_if_provided_value_does_not_have_a_decimal_value()
    {
        $this->expectExceptionMessage('Coordinates does not follow expected pattern');
        CoordinateFormatter::format('12');
    }
}
