<?php declare(strict_types=1);

namespace alecwcp;

class Date implements DateInterface
{
    public const FORMAT = 'Y-m-d';

    private $year = 0;
    private $month = 0;
    private $day = 0;

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

    public static function createFromDateInterface(DateInterface $date): Date
    {
        return static::createFromFormat(static::FORMAT, $date->format(static::FORMAT));
    }

    public static function createFromDateTimeInterface(\DateTimeInterface $dateTime): Date
    {
        return static::createFromFormat(static::FORMAT, $dateTime->format(static::FORMAT));
    }

    public function diff(DateInterface $date2, bool $absolute = false): \DateInterval
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime1 = \DateTimeImmutable::createFromFormat(static::FORMAT, $this->format(static::FORMAT), $utc)->setTime(0, 0);
        $dateTime2 = \DateTimeImmutable::createFromFormat(static::FORMAT, $this->format(static::FORMAT), $utc)->setTime(0, 0);
        return $dateTime1->diff($dateTime2, $absolute);
    }

    public function format(string $format): string
    {
        $utc = new \DateTimeZone('UTC');
        $dateTime = \DateTimeImmutable::createFromFormat(static::FORMAT, sprintf('%d-%d-%d', $this->year, $this->month, $this->day), $utc)->setTime(0, 0);
        return $dateTime->format($format);
    }

    public function equalTo(DateInterface $date2): bool
    {
        if (!$date2 instanceof Date) {
            $date2 = static::createFromDateInterface($date2);
        }
        return $date2->year === $this->year
            && $date2->month === $this->month
            && $date2->day === $this->day;
    }

    public function lessThan(DateInterface $date2): bool
    {
        if (!$date2 instanceof Date) {
            $date2 = static::createFromDateInterface($date2);
        }
        if ($this->year < $date2->year) {
            return true;
        }
        if ($this->month < $date2->month) {
            return true;
        }
        return $this->day < $date2->day;
    }

    public function lessThanOrEqualTo(DateInterface $date2): bool
    {
        return $this->lessThan($date2) || $this->equalTo($date2);
    }

    public function greaterThan(DateInterface $date2): bool
    {
        return !$this->lessThanOrEqualTo($date2);
    }

    public function greaterThanOrEqualTo(DateInterface $date2): bool
    {
        return !$this->lessThan($date2);
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getMonth(): int
    {
        return $this->year;
    }

    public function getDay(): int
    {
        return $this->year;
    }
}
