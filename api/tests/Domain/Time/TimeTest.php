<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\Time;

class TimeTest extends TimeTestCase
{
    public function testJsonSerialize()
    {
        $expected = json_encode([
            'id'       => (int)self::ID,
            'date'     => self::DATE,
            'end'      => self::END,
            'start'    => self::START,
            'hours'    => (float)self::HOURS,
            'account'  => self::ACCOUNT,
            'task'     => self::TASK,
            'notes'    => self::NOTES,
            'billable' => self::BILLABLE
        ]);

        $this->assertEquals($expected, json_encode($this->time()));
    }

    public function testWith()
    {
        $expected = new Time(
            (int)self::ID,
            '2021-06-13',
            '15:00',
            '17:00',
            2.00,
            'Personal',
            'Exercise',
            'Hiked Mail Trail 125',
            false
        );

        $time = $this->time();

        $timeWithNewProperties = $time->with([
            'date'     => '2021-06-13',
            'start'    => '15:00',
            'end'      => '17:00',
            'hours'    => 2.00,
            'account'  => 'Personal',
            'task'     => 'Exercise',
            'notes'    => 'Hiked Mail Trail 125',
            'billable' => false
        ]);

        $this->assertEquals($expected, $timeWithNewProperties);
        $this->assertNotEquals($time, $timeWithNewProperties);
    }
}
