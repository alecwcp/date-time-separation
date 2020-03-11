<?php

declare(strict_types=1);

namespace alecwcp\Tests\Date;

use alecwcp\Date\Date;
use PHPUnit\Framework\TestCase;

class DateTest extends TestCase
{
    /**
     * @dataProvider createFromFormatProvider
     */
    public function testCreateFromFormat(string $format, string $dateString, int $expectedYear, int $expectedMonth, int $expectedDay): void
    {
        $dateObject = Date::createFromFormat($format, $dateString);
        $this->assertEquals($expectedYear, $dateObject->getYear(), 'Year did not match.');
        $this->assertEquals($expectedMonth, $dateObject->getMonth(), 'Month did not match.');
        $this->assertEquals($expectedDay, $dateObject->getDay(), 'Day did not match.');
    }

    public function createFromFormatProvider(): array
    {
        return [
            ['Y-m-d', '2019-02-04', 2019, 2, 4],
            ['jS F Y', '23rd March 2020', 2020, 3, 23],
        ];
    }
}
