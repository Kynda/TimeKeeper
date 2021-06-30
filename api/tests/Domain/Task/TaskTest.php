<?php

declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\Task\Task;
use Tests\TestCase;

class TaskTest extends TestCase
{
    const
        TASK_SHOPPING = 'DayJob',
        TASK_CHORES   = 'Personal'
    ;

    public function testJsonSerialize()
    {
        $expected = json_encode([
            'task' => self::TASK_SHOPPING
        ]);

        $this->assertEquals($expected, json_encode(new Task(self::TASK_SHOPPING)));
    }

    public function testWith()
    {
        $timeTask = new Task(self::TASK_SHOPPING);
        $timeTaskWithNewTask = $timeTask->with(['task' => self::TASK_CHORES]);

        $this->assertEquals(self::TASK_CHORES, $timeTaskWithNewTask->getTask());
        $this->assertEquals(self::TASK_SHOPPING, $timeTask->getTask());
    }
}

