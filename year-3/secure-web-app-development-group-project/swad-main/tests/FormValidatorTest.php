<?php

use PHPUnit\Framework\TestCase;

include '/p3t/phpappfolder/includes/telemetry/app/src/validators/FormValidator.php';



class FormValidatorTest extends TestCase
{
    protected $validator;

    public function setUp(): void
    {
        $this->validator = new FormValidator();
    }

    public function dataForSanitiseUsernameTest(): array
    {
        return [
            ['testing123', 'testing123'],
            ['h435396fdg', 'h435396fdg'],
            [2435, 2435],
            ['<script>alert(1)</script>', null],
            ['----', null],
            ['-- \' OR 1=1 ', null],
            ['PHP: Hypertext Processer', null],
            ['PHPHypertextProcesser', 'PHPHypertextProcesser']
        ];
    }

    /**
     * @dataProvider dataForSanitiseUsernameTest
     */

    public function testSanitiseUsername($input, $expected)
    {
        $validator = $this->validator;
        $this->assertEquals($expected, $validator->sanitiseUsername($input));
    }
}
