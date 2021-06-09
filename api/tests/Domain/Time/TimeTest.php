<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\Time;
use Tests\TestCase;

class TimeTest extends TestCase
{
    const
        ID       = 1,
        DATE     = '2021-01-01',
        START    = '13:00',
        END      = '14:00',
        HOURS    = 1.00,
        ACCOUNT  = 'Dayjob',
        TASK     = 'Ticket',
        NOTES    = 'YOLO',
        BILLABLE = true
    ;

    public function testJsonSerialize()
    {
        $time = new Time(
            self::ID,
            self::DATE,
            self::START,
            self::END,
            self::HOURS,
            self::ACCOUNT,
            self::TASK,
            self::NOTES,
            self::BILLABLE
        );

        $expected = json_encode([
            'id'       => self::ID,
            'date'     => self::DATE,
            'end'      => self::END,
            'start'    => self::START,
            'hours'    => self::HOURS,
            'account'  => self::ACCOUNT,
            'task'     => self::TASK,
            'notes'    => self::NOTES,
            'billable' => self::BILLABLE
        ]);

        $this->assertEquals($expected, json_encode($time));
    }
}
