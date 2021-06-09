<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\Time;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Tests\TestCase;

class TimeTransformerTest extends TestCase
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

    /**
    * @var Time
    */
    private $time;

    public function setUp(): void
    {
        $this->time = new Time(
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
    }

    public function testTransformTimeResource(): void
    {
        $manager = new Manager();

        $baseUrl = 'http://example.com';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));

        $resource = new Item($this->time, new TimeTransformer(), 'time');

        $this->assertEquals(
            [
                'data' => [
                    'type' => 'time',
                    'id'   =>  '1',
                    'attributes' => [
                        'date'     => self::DATE,
                        'end'      => self::END,
                        'start'    => self::START,
                        'hours'    => self::HOURS,
                        'account'  => self::ACCOUNT,
                        'task'     => self::TASK,
                        'notes'    => self::NOTES,
                        'billable' => self::BILLABLE
                    ],
                    'links' => [
                        'self' => 'http://example.com/time/1',
                    ],
                ],
            ],
            $manager->createData($resource)->toArray()
        );
    }
}
