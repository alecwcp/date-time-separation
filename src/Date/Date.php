<?php

declare(strict_types=1);

namespace alecwcp\Date;

use alecwcp\Exception\Exception;
use alecwcp\Exception\InvalidArgumentException;

/**
 * Class Date
 * @package alecwcp\Date
 */
class Date implements DateInterface
{
    /** @var string */
    public const FORMAT = 'Y-m-d';

    /** @var int $year */
    private $year = 0;
    /** @var int $month */
    private $month = 0;
    /** @var int $day */
    private $day = 0;

    /**
     * Date constructor.
     * @param int $year
     * @param int $month
     * @param int $day
     */
    public function __construct(int $year, int $month, int $day)
    {
        $this->year = $year;
        $this->month = $month;
        $this->day = $day;
    }

    /**
     * @param string $format
     * @param string $date
     * @return Date
     * @throws Exception
     */
    public static function createFromFormat(string $format, string $date): Date
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat($format, $date, $utc);
        if (false === $dateTime) {
            throw new Exception(sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, $format));
        }
        $dateTime = $dateTime->setTime(0, 0);
        $matches = [];
        preg_match('/(\d{4})-(\d{2})-(\d{2})/', $dateTime->format(self::FORMAT), $matches);
        return new static((int) $matches[1], (int) $matches[2], (int) $matches[3]);
    }

    /**
     * @param \DateTimeInterface|DateInterface $date
     * @return Date
     * @throws InvalidArgumentException|Exception
     */
    public static function createFromInterface(object $date): Date
    {
        self::checkInterfaceType(__METHOD__, $date);
        return static::createFromFormat(self::FORMAT, $date->format(self::FORMAT));
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function makeDateTimeInterface(): \DateTimeInterface
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $this->format(self::FORMAT),
            $utc
        );
        if (false === $dateTime) {
            throw new Exception(sprintf('Failed to create %s from %s', \DateTimeImmutable::class, self::class));
        }
        $dateTime = $dateTime->setTime(0, 0);
        return $dateTime;
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function diff(object $date2, bool $absolute = false): \DateInterval
    {
        self::checkInterfaceType(__METHOD__, $date2);

        $utc = new \DateTimeZone('UTC');
        $dateTime1 = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $this->format(self::FORMAT),
            $utc
        );
        if (false === $dateTime1) {
            throw new Exception(
                sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, self::FORMAT)
            );
        }
        $dateTime1 = $dateTime1->setTime(0, 0);
        $dateTime2 = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $date2->format(self::FORMAT),
            $utc
        );
        if (false === $dateTime2) {
            throw new Exception(
                sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, self::FORMAT)
            );
        }
        $dateTime2 = $dateTime2->setTime(0, 0);
        return $dateTime1->diff($dateTime2, $absolute);
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function format(string $format): string
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            sprintf('%d-%d-%d', $this->year, $this->month, $this->day),
            $utc
        );
        if (false === $dateTime) {
            throw new Exception(
                sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, self::FORMAT)
            );
        }
        $dateTime = $dateTime->setTime(0, 0);
        return $dateTime->format($format);
    }

    /**
     * @param \DateTimeInterface|DateInterface $date1
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return bool
     */
    public static function equal(object $date1, object ...$dates): bool
    {
        array_unshift($dates, $date1);
        $dates = self::transformInterfacesToTimes(...$dates);
        $date1 = array_shift($dates);

        foreach ($dates as $key => $date) {
            $equalTo = $date->year === $date1->year
                && $date->month === $date1->month
                && $date->day === $date1->day;
            if (!$equalTo) {
                return false;
            }
            $date1 = $date;
        }
        return true;
    }

    /**
     * @param \DateTimeInterface|DateInterface $date1
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return bool
     */
    public static function lessThan(object $date1, object ...$dates): bool
    {
        array_unshift($dates, $date1);
        $dates = self::transformInterfacesToTimes(...$dates);
        $date1 = array_shift($dates);

        foreach ($dates as $key => $date) {
            if ($date1->year >= $date->year) {
                return false;
            } elseif ($date1->month >= $date->month) {
                return false;
            } elseif ($date1->day >= $date->day) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param \DateTimeInterface|DateInterface $date1
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return bool
     */
    public static function lessThanOrEqual(object $date1, object ...$dates): bool
    {
        array_unshift($dates, $date1);
        return self::lessThan(...$dates) || self::equal(...$dates);
    }

    /**
     * @param \DateTimeInterface|DateInterface $date1
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return bool
     */
    public function greaterThan(object $date1, object ...$dates): bool
    {
        array_unshift($dates, $date1);
        return !self::lessThanOrEqual(...$dates);
    }

    /**
     * @param \DateTimeInterface|DateInterface $date1
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return bool
     */
    public function greaterThanOrEqual(object $date1, object ...$dates): bool
    {
        array_unshift($dates, $date1);
        return !self::lessThan(...$dates);
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @param string $method
     * @param \DateTimeInterface|DateInterface ...$dates
     */
    private static function checkInterfaceType(string $method, object ...$dates): void
    {
        foreach ($dates as $date) {
            if (!$date instanceof DateInterface && !$date instanceof \DateTimeInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Expected %s or %s to be passed to %s. Got %s..',
                        DateInterface::class,
                        \DateTimeInterface::class,
                        $method,
                        'object' === gettype($date) ? get_class($date) : gettype($date)
                    )
                );
            }
        }
    }

    /**
     * @param \DateTimeInterface|DateInterface ...$dates
     * @return Date[]
     */
    private static function transformInterfacesToTimes(...$dates): array
    {
        return array_map(
            function (object $date): Date {
                if (!$date instanceof Date) {
                    $date = static::createFromInterface($date);
                }
                return $date;
            },
            $dates
        );
    }

    /**
     * @return int
     */
    public function getYear(): int
    {
        return $this->year;
    }

    /**
     * @return int
     */
    public function getMonth(): int
    {
        return $this->month;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->day;
    }
}
