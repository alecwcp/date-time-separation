<?php

declare(strict_types=1);

namespace alecwcp\Tests\Date;

use alecwcp\Date\Date;
use PHPUnit\Framework\TestCase;

/**
 * Class DateTest
 * @package alecwcp\Tests\Date
 */
class DateTest extends TestCase
{
    /**
     * @dataProvider createFromFormatProvider
     *
     * @param string $format
     * @param string $dateString
     * @param int $expectedYear
     * @param int $expectedMonth
     * @param int $expectedDay
     * @throws \alecwcp\Exception\Exception
     */
    public function testCreateFromFormat(string $format, string $dateString, int $expectedYear, int $expectedMonth, int $expectedDay): void
    {
        $dateObject = Date::createFromFormat($format, $dateString);
        $this->assertEquals($expectedYear, $dateObject->getYear(), 'Year did not match.');
        $this->assertEquals($expectedMonth, $dateObject->getMonth(), 'Month did not match.');
        $this->assertEquals($expectedDay, $dateObject->getDay(), 'Day did not match.');
    }

    /**
     * @return array
     */
    public function createFromFormatProvider(): array
    {
        return [
            ['Y-m-d', '2019-02-04', 2019, 2, 4],
            ['jS F Y', '23rd March 2020', 2020, 3, 23],
        ];
    }

    /**
     * @dataProvider createFromInterfaceProvider
     *
     * @param object $date
     * @param int $expectedYear
     * @param int $expectedMonth
     * @param int $expectedDay
     * @throws \alecwcp\Exception\Exception
     */
    public function testCreateFromInterface(object $date, int $expectedYear, int $expectedMonth, int $expectedDay): void
    {
        $dateObject = Date::createFromInterface($date);
        $this->assertEquals($expectedYear, $dateObject->getYear(), 'Year did not match.');
        $this->assertEquals($expectedMonth, $dateObject->getMonth(), 'Month did not match.');
        $this->assertEquals($expectedDay, $dateObject->getDay(), 'Day did not match.');
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function createFromInterfaceProvider(): array
    {
        return [
            [new \DateTime('2020-01-12', new \DateTimeZone('UTC')), 2020, 1, 12],
            [new \DateTimeImmutable('2019-11-10', new \DateTimeZone('UTC')), 2019, 11, 10],
            [new Date(1969, 3, 7), 1969, 3, 7],
        ];
    }

    /**
     * @dataProvider makeDateTimeInterfaceProvider
     *
     * @param Date $date
     * @param string $format
     * @param string $expectedDateTime
     * @throws \alecwcp\Exception\Exception
     */
    public function testMakeDateTimeInterface(Date $date, string $format, string $expectedDateTime): void
    {
        $this->assertEquals($expectedDateTime, $date->makeDateTimeInterface()->format($format));
    }

    /**
     * @return array
     */
    public function makeDateTimeInterfaceProvider(): array
    {
        return [
            [new Date(2020, 1, 12), 'Y-m-d\TH:i:s.uP', '2020-01-12T00:00:00.000000+00:00'],
            [new Date(2019, 11, 10), 'Y-m-d\TH:i:s.uP', '2019-11-10T00:00:00.000000+00:00'],
            [new Date(1969, 3, 7), 'Y-m-d\TH:i:s.uP', '1969-03-07T00:00:00.000000+00:00'],
        ];
    }

    /**
     * @dataProvider diffProvider
     *
     * @param object $date
     * @param object $date2
     * @param bool $absolute
     * @param string $format
     * @param string $expectedDateTimeInterval
     */
    public function testDiff(object $date, object $date2, bool $absolute, string $format, string $expectedDateTimeInterval): void
    {
        $this->assertEquals($expectedDateTimeInterval, $date->diff($date2, $absolute)->format($format));
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function diffProvider(): array
    {
        return [
            [new Date(2020, 1, 12), new \DateTime('2020-01-14', new \DateTimeZone('UTC')), false, '%R%y-%m-%d %h:%i:%s.%f', '+0-0-2 0:0:0.0'],
            [new Date(2019, 1, 10), new \DateTimeImmutable('2020-01-06', new \DateTimeZone('UTC')), false, '%R%y-%m-%d %h:%i:%s.%f', '+0-11-27 0:0:0.0'],
            [new Date(2019, 1, 10), new Date(1969, 5, 6), false, '%R%y-%m-%d %h:%i:%s.%f', '-49-8-4 0:0:0.0'],
        ];
    }
}
