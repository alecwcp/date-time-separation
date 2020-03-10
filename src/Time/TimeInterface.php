<?php

declare(strict_types=1);

namespace alecwcp\Time;

interface TimeInterface
{
    public function diff(TimeInterface $time2, bool $absolute = false): \DateInterval;
    public function format(string $format): string;
    public function equalTo(TimeInterface $time2): bool;
    public function lessThan(TimeInterface $time2): bool;
    public function lessThanOrEqualTo(TimeInterface $time2): bool;
    public function greaterThan(TimeInterface $time2): bool;
    public function greaterThanOrEqualTo(TimeInterface $time2): bool;
}
