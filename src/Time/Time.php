<?php

declare(strict_types=1);

namespace alecwcp\Time;

class Time implements TimeInterface
{
    public const FORMAT = 'H:i:s.u';

    private $hour = 0;
    private $minute = 0;
    private $second = 0;
    private $microSecond = 0;

    public static function createFromFormat(string $format, string $date): Time
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat($format, $date, $utc)->setDate(0, 0, 0);
        $matches = [];
        preg_match('/(\d{2}):(\d{2}):(\d{2}).(\d{6})/', $dateTime->format(static::FORMAT), $matches);
        $time = new static();
        $time->hour = (int) $matches[1];
        $time->minute = (int) $matches[2];
        $time->second = (int) $matches[3];
        $time->microSecond = (int) $matches[4];
        return $time;
    }

    public static function createFromTimeInterface(TimeInterface $time): Time
    {
        return static::createFromFormat(static::FORMAT, $time->format(static::FORMAT));
    }

    public static function createFromDateTimeInterface(\DateTimeInterface $dateTime): Time
    {
        return static::createFromFormat(static::FORMAT, $dateTime->format(static::FORMAT));
    }

    public function diff(TimeInterface $time2, bool $absolute = false): \DateInterval
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime1 = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            $this->format(static::FORMAT),
            $utc
        )->setDate(0, 0, 0);
        $dateTime2 = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            $this->format(static::FORMAT),
            $utc
        )->setDate(0, 0, 0);
        return $dateTime1->diff($dateTime2, $absolute);
    }

    public function format(string $format): string
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat(
            static::FORMAT,
            sprintf('%d:%d:%d.%d', $this->hour, $this->minute, $this->second, $this->microSecond),
            $utc
        )->setDate(0, 0, 0);
        return $dateTime->format($format);
    }

    public function equalTo(TimeInterface $time2): bool
    {
        if (!$time2 instanceof Date) {
            $time2 = static::createFromTimeInterface($time2);
        }
        return $time2->hour === $this->hour
            && $time2->minute === $this->minute
            && $time2->second === $this->second
            && $time2->microSecond === $this->microSecond;
    }

    public function lessThan(TimeInterface $time2): bool
    {
        if (!$time2 instanceof Time) {
            $time2 = static::createFromTimeInterface($time2);
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

    public function lessThanOrEqualTo(TimeInterface $time2): bool
    {
        return $this->lessThan($time2) || $this->equalTo($time2);
    }

    public function greaterThan(TimeInterface $time2): bool
    {
        return !$this->lessThanOrEqualTo($time2);
    }

    public function greaterThanOrEqualTo(TimeInterface $time2): bool
    {
        return !$this->lessThan($time2);
    }

    public function getHour(): int
    {
        return $this->hour;
    }

    public function getMinute(): int
    {
        return $this->minute;
    }

    public function getSecond(): int
    {
        return $this->second;
    }

    public function getMicroSecond(): int
    {
        return $this->microSecond;
    }
}
