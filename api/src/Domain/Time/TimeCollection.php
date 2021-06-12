<?php

declare(strict_types=1);

namespace App\Domain\Time;

use ArrayIterator;

class TimeCollection extends ArrayIterator
{
    private $dailyMean;

    private $dailyMedian;

    private $dailyMode;

    private $dailyTotals;

    private $totalAverages;

    private $taskTotals;

    private $totalHours;

    private $totalHoursBillable;

    private $totalHoursNonBillbable;

    private $totalHoursShort;

    private $totalHoursShortBillablePercent;

    private $totalHoursShortPercent;

    public function dailyMean(): float
    {

    }

    public function dailyMedian(): float
    {

    }

    public function dailyMode(): float
    {

    }

    public function dailyTotals(): array
    {

    }

    public function offsetGet ($index)
    {
        $row = parent::offsetGet($index);

        if ($row instanceof Time) {
            return $row;
        }

        $time = new Time(
            (int)$row['id'],
            $row['date'],
            $row['start'],
            $row['end'],
            (float)$row['hours'],
            $row['account'],
            $row['task'],
            $row['notes'],
            (bool)$row['billable']
        );

        parent::offsetSet($index, $time);

        return $time;
    }

    public function taskTotals(): array
    {

    }

    public function taskAverages(): array
    {

    }

    public function totalHours(): float
    {

    }

    public function totalHoursBillable(): float
    {

    }

    public function totalHoursNonBillable(): float
    {

    }

    public function totalHoursShort(): float
    {

    }

    public function totalHoursShortPercent(): float
    {

    }

    public function totalHoursShortBillable(): float
    {

    }

    public function totalHoursShortBillablePercent(): float
    {

    }
}
