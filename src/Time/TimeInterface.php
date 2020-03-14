<?php

declare(strict_types=1);

namespace alecwcp\Time;

/**
 * Interface TimeInterface
 * @package alecwcp\Time
 */
interface TimeInterface
{
    /**
     * @param \DateTimeInterface|TimeInterface $time2
     * @param bool $absolute
     * @return \DateInterval
     */
    public function diff(object $time2, bool $absolute = false): \DateInterval;

    /**
     * @param string $format
     * @return string
     */
    public function format(string $format): string;

    /**
     * @param \DateTimeInterface[]|TimeInterface[] $times
     * @return bool
     */
    public static function equalTo(object ...$times): bool;

    /**
     * @param \DateTimeInterface[]|TimeInterface[] $times
     * @return bool
     */
    public static function lessThan(object ...$times): bool;

    /**
     * @param \DateTimeInterface[]|TimeInterface[] $times
     * @return bool
     */
    public static function lessThanOrEqualTo(object ...$times): bool;

    /**
     * @param \DateTimeInterface[]|TimeInterface[] $times
     * @return bool
     */
    public function greaterThan(object ...$times): bool;

    /**
     * @param \DateTimeInterface[]|TimeInterface[] $times
     * @return bool
     */
    public function greaterThanOrEqualTo(object...$times): bool;
}
