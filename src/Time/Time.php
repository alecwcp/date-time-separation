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
        $time = new static();
        $time->hour = (int) $matches[1];
        $time->minute = (int) $matches[2];
        $time->second = (int) $matches[3];
        $time->microSecond = (int) $matches[4];
        return $time;
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @param \DateTimeInterface|TimeInterface $time
     * @return Time
     * @throws InvalidArgumentException|Exception
     */
    public function createFromInterface(object $time): Time
    {
        if (!$time instanceof TimeInterface && !$time instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return static::createFromFormat(self::FORMAT, $time->format(self::FORMAT));
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function diff(object $time2, bool $absolute = false): \DateInterval
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

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
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function equalTo(object $time2): bool
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        if (!$time2 instanceof Time) {
            $time2 = static::createFromInterface($time2);
        }
        return $time2->hour === $this->hour
            && $time2->minute === $this->minute
            && $time2->second === $this->second
            && $time2->microSecond === $this->microSecond;
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function lessThan(object $time2): bool
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        if (!$time2 instanceof Time) {
            $time2 = static::createFromInterface($time2);
        }
        if ($this->hour < $time2->hour) {
            return true;
        }
        if ($this->minute < $time2->minute) {
            return true;
        }
        if ($this->second < $time2->second) {
            return true;
        }
        return $this->microSecond < $time2->microSecond;
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function lessThanOrEqualTo(object $time2): bool
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return $this->lessThan($time2) || $this->equalTo($time2);
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function greaterThan(object $time2): bool
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return !$this->lessThanOrEqualTo($time2);
    }

    /**
     * @psalm-suppress DocblockTypeContradiction
     * @inheritDoc
     * @throws InvalidArgumentException|Exception
     */
    public function greaterThanOrEqualTo(object $time2): bool
    {
        if (!$time2 instanceof TimeInterface && !$time2 instanceof \DateTimeInterface) {
            throw new InvalidArgumentException(
                sprintf(
                    'First argument passed to %s must be instance of %s or %s.',
                    __METHOD__,
                    TimeInterface::class,
                    \DateTimeInterface::class
                )
            );
        }

        return !$this->lessThan($time2);
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
