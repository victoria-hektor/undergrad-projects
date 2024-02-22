<?php

/**
 * PHPUnit Test for the creation of a xml string message and the cleaning of params via an array
 * Settings are in phpunit.xml in root dir
 * Test is set up to catch exception
 *
 * @testCreateXmlMessage
 * @testCleanTelemetryFormParameters
 */

namespace Test;


use PHPUnit\Framework\TestCase;
use TelemetryModel\TelemetryValidator;

include 'telemetry/app/src/validators/TelemetryValidator.php';



class CreateTelemetryModelTest extends TestCase
{
    protected $validator;


    public function setUp(): void
    {
        $this->validator = new TelemetryValidator();
    }


    // Testing to see if allows string
    public function testDataForTelemetryTemperature(): array
    {
       return [
           [2555, '-273'],
           [-30, -30],
           [24, 24],
           [100, 100],
           ['test', 0],
           ['', 0],
           [-300, -273]
       ];
    }

    /**
     * @dataProvider testDataForTelemetryTemperature
     */

    public function testTemperatureValidation($input, $expected): void
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateTemperature($input));
    }



    public function testDataForIntValue(): array
    {
        return [
            ['abcdef', 0],
            [435, 435],
            ['h63llo', 0],
            [6578888888, 6578888888],
            [-500, -500],
            ['35^%$^%$dgf', 0],
            ['', 0]

        ];

    }

    /**
     * @dataProvider testDataForIntValue
     */

    public function testIntNumberValidation($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateNumber($input));
    }


//    /**
//     * @dataProvider testStringData
//     */
//    /* NB: Code can be redeveloped to validate further to ensure only the fan direction can be input */
//    public function testStringData(): array
//    {
//        return [
//            ['zyxv', 'forward'],
//            ['0011', 'forward'],
//            ['@!"£', 'reverse'],
//            ['??|//', 'reverse']
//        ];
//    }
//
//    public function testStringValidator($input, $expected)
//    {
//        $validator = $this->validator;
//        $this->assertEquals($expected, $validator->validateString($input));
//
//    }


    public function testDateData(): array
    {
        return [
            ['11-11-11 @:!!:2', 'N/A'],
            ['@-!!-?? 01:!!:2', 'N/A'],
            ['11-11-11 @:!!:2', 'N/A'],
            ['', 'N/A'],
            ['b0bc@t 2222222', 'N/A'],
            ['17/12/2022 09:55:22', '2022-12-17 09:55:22'],
            ['01/02/2022 09:55:22', '2022-02-01 09:55:22']
        ];

    }

    /**
     * @dataProvider testDateData
     */
    public function testDateValidator($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateDate($input));
    }


    public function testDataForSwitch(): array
    {
        return [
            ['on', 'on'],
            ['off', 'off'],
            ['abd', 'N/A'],
            [123, 'N/A'],
            ['£$%', 'N/A'],
            ['', 'N/A'],
            ['<script>alert(1)</script>', 'N/A']
        ];

    }

    /**
     * @dataProvider testDataForSwitch
     */
    public function testSwitchValidation($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateSwitch($input));
    }


    public function testDataForFanDirection(): array
    {
        return [
            ['forward', 'forward'],
            ['reverse', 'reverse'],
            ['donkey', 'N/A'],
            [1234, "N/A"],
            ['£$%^^', 'N/A'],
        ];

    }


    /**
     * @dataProvider testDataForFanDirection
     */

    public function testFanDirectionValidation($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateFanDirection($input));
    }
}
