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
     * @param \DateTimeInterface|TimeInterface $time2
     * @return bool
     */
    public function equalTo(object $time2): bool;

    /**
     * @param \DateTimeInterface|TimeInterface $time2
     * @return bool
     */
    public function lessThan(object $time2): bool;

    /**
     * @param \DateTimeInterface|TimeInterface $time2
     * @return bool
     */
    public function lessThanOrEqualTo(object $time2): bool;

    /**
     * @param \DateTimeInterface|TimeInterface $time2
     * @return bool
     */
    public function greaterThan(object $time2): bool;

    /**
     * @param \DateTimeInterface|TimeInterface $time2
     * @return bool
     */
    public function greaterThanOrEqualTo(object $time2): bool;
}
