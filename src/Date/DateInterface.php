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
     * @param \DateTimeInterface|DateInterface $date2
     * @return bool
     */
    public function equalTo(object $date2): bool;

    /**
     * @param \DateTimeInterface|DateInterface $date2
     * @return bool
     */
    public function lessThan(object $date2): bool;

    /**
     * @param \DateTimeInterface|DateInterface $date2
     * @return bool
     */
    public function lessThanOrEqualTo(object $date2): bool;

    /**
     * @param \DateTimeInterface|DateInterface $date2
     * @return bool
     */
    public function greaterThan(object $date2): bool;

    /**
     * @param \DateTimeInterface|DateInterface $date2
     * @return bool
     */
    public function greaterThanOrEqualTo(object $date2): bool;
}
