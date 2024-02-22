<?php

use PHPUnit\Framework\TestCase;

include '/p3t/phpappfolder/includes/telemetry/app/src/validators/SoapValidator.php';

class ValidateTeamNameTest extends TestCase
{

    protected $validator;

    public function setUp(): void
    {
        $this->validator = new SoapValidator();
    }

    public function dataForTeamNameInputTest(): array
    {
        return [
            ['21-3110-AC', true],
            ['abcdefg', false],
            ['12345', false],
            ['£cje152', false],
            ['', false],
        ];
    }

    /**
     * @dataProvider dataForTeamNameInputTest
     */

    public function testTeamNameValidator($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->validateId($input));
    }


}