<?php

declare(strict_types=1);

namespace alecwcp;

interface DateInterface
{
    public function diff(DateInterface $date2, bool $absolute = false): \DateInterval;
    public function format(string $format): string;
    public function equalTo(DateInterface $date2): bool;
    public function lessThan(DateInterface $date2): bool;
    public function lessThanOrEqualTo(DateInterface $date2): bool;
    public function greaterThan(DateInterface $date2): bool;
    public function greaterThanOrEqualTo(DateInterface $date2): bool;
}
