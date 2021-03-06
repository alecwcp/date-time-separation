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
     * @return \DateTimeInterface
     */
    public function makeDateTimeInterface(): \DateTimeInterface;
}
