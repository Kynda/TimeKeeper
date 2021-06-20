<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\Time;
use Prophecy\PhpUnit\ProphecyTrait;
use Tests\TestCase;

abstract class TimeTestCase extends TestCase
{
    use ProphecyTrait;

    const
        ID       = '1',
        DATE     = '2021-01-01',
        START    = '13:00',
        END      = '14:00',
        HOURS    = '1.00',
        ACCOUNT  = 'Dayjob',
        TASK     = 'Ticket',
        NOTES    = 'Code Review',
        BILLABLE = true
    ;

    protected $raw = [
        'id'       => self::ID,
        'date'     => self::DATE,
        'start'    => self::START,
        'end'      => self::END,
        'hours'    => self::HOURS,
        'account'  => self::ACCOUNT,
        'task'     => self::TASK,
        'notes'    => self::NOTES,
        'billable' => self::BILLABLE
    ];

    protected $request = [
        'date'     => self::DATE,
        'start'    => self::START,
        'end'      => self::END,
        'hours'    => self::HOURS,
        'account'  => self::ACCOUNT,
        'task'     => self::TASK,
        'notes'    => self::NOTES,
        'billable' => self::BILLABLE,
        'extra'    => 'Extra submitted field'
    ];

    protected function time(): Time
    {
       return new Time(
            (int)self::ID,
            self::DATE,
            self::START,
            self::END,
            (float)self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::NOTES,
            self::BILLABLE
        );
    }
}
