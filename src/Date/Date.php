<?php

declare(strict_types=1);

namespace alecwcp\Date;

use alecwcp\Exception\InvalidArgumentException;

/**
 * Class Date
 * @package alecwcp\Date
 */
class Date implements DateInterface
{
    public const FORMAT = 'Y-m-d';

    /** @var int $year */
    private $year = 0;
    /** @var int $month */
    private $month = 0;
    /** @var int $day */
    private $day = 0;

    /**
     * @param string $format
     * @param string $date
     * @return Date
     */
    public static function createFromFormat(string $format, string $date): Date
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat($format, $date, $utc)->setTime(0, 0);
        $matches = [];
        preg_match('/(\d{4})-(\d{2})-(\d{2})/', $dateTime->format(static::FORMAT), $matches);
        $date = new static();
        $date->year = (int) $matches[1];
        $date->month = (int) $matches[2];
        $date->day = (int) $matches[3];
        return $date;
    }

    /**
     * @param \DateTimeInterface|DateInterface $date
     * @return Date
     */
    public function createFromInterface(object $date): Date
    {
        if (!$date instanceof DateInterface && !$date instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return static::createFromFormat(static::FORMAT, $date->format(static::FORMAT));
    }

    /**
     * @inheritDoc
     */
    public function diff(object $date2, bool $absolute = false): \DateInterval
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        $utc = new \DateTimeZone('UTC');
        $dateTime1 = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            $this->format(static::FORMAT),
            $utc
        )->setTime(0, 0);
        $dateTime2 = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            $date2->format(static::FORMAT),
            $utc
        )->setTime(0, 0);
        return $dateTime1->diff($dateTime2, $absolute);
    }

    /**
     * @inheritDoc
     */
    public function format(string $format): string
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            sprintf('%d-%d-%d', $this->year, $this->month, $this->day),
            $utc
        )->setTime(0, 0);
        return $dateTime->format($format);
    }

    /**
     * @inheritDoc
     */
    public function equalTo(object $date2): bool
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        if (!$date2 instanceof Date) {
            $date2 = static::createFromInterface($date2);
        }
        return $date2->year === $this->year
            && $date2->month === $this->month
            && $date2->day === $this->day;
    }

    /**
     * @inheritDoc
     */
    public function lessThan(object $date2): bool
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        if (!$date2 instanceof Date) {
            $date2 = static::createFromInterface($date2);
        }
        if ($this->year < $date2->year) {
            return true;
        }
        if ($this->month < $date2->month) {
            return true;
        }
        return $this->day < $date2->day;
    }

    /**
     * @inheritDoc
     */
    public function lessThanOrEqualTo(object $date2): bool
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return $this->lessThan($date2) || $this->equalTo($date2);
    }

    /**
     * @inheritDoc
     */
    public function greaterThan(object $date2): bool
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return !$this->lessThanOrEqualTo($date2);
    }

    /**
     * @inheritDoc
     */
    public function greaterThanOrEqualTo(object $date2): bool
    {
        if (!$date2 instanceof DateInterface && !$date2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    DateInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return !$this->lessThan($date2);
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
        return $this->year;
    }

    /**
     * @return int
     */
    public function getDay(): int
    {
        return $this->year;
    }
}
