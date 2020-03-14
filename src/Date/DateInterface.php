<?php

declare(strict_types=1);

namespace alecwcp\Date;

/**
 * Interface DateInterface
 * @package alecwcp\Date
 */
interface DateInterface
{
    /**
     * @param \DateTimeInterface|DateInterface $date2
     * @param bool $absolute
     * @return \DateInterval
     */
    public function diff(object $date2, bool $absolute = false): \DateInterval;

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
