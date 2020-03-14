<?php

declare(strict_types=1);

namespace alecwcp\Time;

use alecwcp\Exception\Exception;
use alecwcp\Exception\InvalidArgumentException;

/**
 * Class Time
 * @package alecwcp\Time
 */
class Time implements TimeInterface
{
    public const FORMAT = 'H:i:s.u';

    /** @var int $hour */
    private $hour = 0;
    /** @var int $minute */
    private $minute = 0;
    /** @var int $second */
    private $second = 0;
    /** @var int $microSecond */
    private $microSecond = 0;

    public function __construct(int $hour, int $minute, int $second = 0, int $microSecond = 0)
    {
        $this->hour = $hour;
        $this->minute = $minute;
        $this->second = $second;
        $this->microSecond = $microSecond;
    }

    /**
     * @param string $format
     * @param string $date
     * @return Time
     * @throws Exception
     */
    public static function createFromFormat(string $format, string $date): Time
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat($format, $date, $utc);
        if (false === $dateTime) {
            throw new Exception(sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, $format));
        }
        $dateTime = $dateTime->setDate(0, 0, 0);
        $matches = [];
        preg_match('/(\d{2}):(\d{2}):(\d{2}).(\d{6})/', $dateTime->format(self::FORMAT), $matches);
        return new static((int) $matches[1], (int) $matches[2], (int) $matches[3], (int) $matches[4]);
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time
     * @return Time
     * @throws InvalidArgumentException|Exception
     */
    public static function createFromInterface(object $time): Time
    {
        self::checkInterfaceType(__METHOD__, $time);
        return static::createFromFormat(self::FORMAT, $time->format(self::FORMAT));
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
        $dateTime = $dateTime->setDate(0, 0, 0);
        return $dateTime;
    }

    /**
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function diff(object $time2, bool $absolute = false): \DateInterval
    {
        self::checkInterfaceType(__METHOD__, $time2);

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
        $dateTime1 = $dateTime1->setDate(0, 0, 0);
        $dateTime2 = \DateTimeImmutable::createFromFormat(
            self::FORMAT,
            $time2->format(self::FORMAT),
            $utc
        );
        if (false === $dateTime2) {
            throw new Exception(
                sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, self::FORMAT)
            );
        }
        $dateTime2 = $dateTime2->setDate(0, 0, 0);
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
            sprintf('%d:%d:%d.%d', $this->hour, $this->minute, $this->second, $this->microSecond),
            $utc
        );
        if (false === $dateTime) {
            throw new Exception(
                sprintf('Failed to create %s from format %s.', \DateTimeImmutable::class, self::FORMAT)
            );
        }
        $dateTime = $dateTime->setDate(0, 0, 0);
        return $dateTime->format($format);
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time1
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return bool
     */
    public static function equal(object $time1, object ...$times): bool
    {
        array_unshift($times, $time1);
        $times = self::transformInterfacesToTimes(...$times);
        $time1 = array_shift($times);

        foreach ($times as $key => $time) {
            $equalTo = $time->hour === $time1->hour
                && $time->minute === $time1->minute
                && $time->second === $time1->second
                && $time->microSecond === $time1->microSecond;
            if (!$equalTo) {
                return false;
            }
            $time1 = $time;
        }
        return true;
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time1
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return bool
     */
    public static function lessThan(object $time1, object ...$times): bool
    {
        array_unshift($times, $time1);
        $times = self::transformInterfacesToTimes(...$times);
        $time1 = array_shift($times);

        foreach ($times as $key => $time) {
            if ($time1->hour >= $time->hour) {
                return false;
            } elseif ($time1->minute >= $time->minute) {
                return false;
            } elseif ($time1->second >= $time->second) {
                return false;
            } elseif ($time1->microSecond >= $time->microSecond) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time1
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return bool
     */
    public static function lessThanOrEqual(object $time1, object ...$times): bool
    {
        array_unshift($times, $time1);
        return self::lessThan(...$times) || self::equal(...$times);
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time1
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return bool
     */
    public function greaterThan(object $time1, object ...$times): bool
    {
        array_unshift($times, $time1);
        return !self::lessThanOrEqual(...$times);
    }

    /**
     * @param \DateTimeInterface|TimeInterface $time1
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return bool
     */
    public function greaterThanOrEqual(object $time1, object ...$times): bool
    {
        array_unshift($times, $time1);
        return !self::lessThan(...$times);
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @param string $method
     * @param \DateTimeInterface|TimeInterface ...$times
     */
    private static function checkInterfaceType(string $method, object ...$times): void
    {
        foreach ($times as $time) {
            if (!$time instanceof TimeInterface && !$time instanceof \DateTimeInterface) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Expected %s or %s to be passed to %s. Got %s..',
                        TimeInterface::class,
                        \DateTimeInterface::class,
                        $method,
                        'object' === gettype($time) ? get_class($time) : gettype($time)
                    )
                );
            }
        }
    }

    /**
     * @param \DateTimeInterface|TimeInterface ...$times
     * @return Time[]
     */
    private static function transformInterfacesToTimes(...$times): array
    {
        return array_map(
            function (object $time): Time {
                if (!$time instanceof Time) {
                    $time = static::createFromInterface($time);
                }
                return $time;
            },
            $times
        );
    }

    /**
     * @return int
     */
    public function getHour(): int
    {
        return $this->hour;
    }

    /**
     * @return int
     */
    public function getMinute(): int
    {
        return $this->minute;
    }

    /**
     * @return int
     */
    public function getSecond(): int
    {
        return $this->second;
    }

    /**
     * @return int
     */
    public function getMicroSecond(): int
    {
        return $this->microSecond;
    }
}
