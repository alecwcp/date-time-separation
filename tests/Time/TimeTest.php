<?php

declare(strict_types=1);

namespace alecwcp\Tests\Time;

use alecwcp\Time\Time;
use PHPUnit\Framework\TestCase;

class TimeTest extends TestCase
{
    /**
     * @dataProvider createFromFormatProvider
     */
    public function testCreateFromFormat(string $format, string $timeString, int $expectedHour, int $expectedMinute, int $expectedSecond, int $expectedMicroSecond): void
    {
        $timeObject = Time::createFromFormat($format, $timeString);
        $this->assertEquals($expectedHour, $timeObject->getHour(), 'Hour did not match.');
        $this->assertEquals($expectedMinute, $timeObject->getMinute(), 'Minute did not match.');
        $this->assertEquals($expectedSecond, $timeObject->getSecond(), 'Second did not match.');
        $this->assertEquals($expectedMicroSecond, $timeObject->getMicroSecond(), 'Microsecond did not match.');
    }

    public function createFromFormatProvider(): array
    {
        return [
            ['H:i:s.u', '23:45:12.123567', 23, 45, 12, 123567],
        ];
    }
}
