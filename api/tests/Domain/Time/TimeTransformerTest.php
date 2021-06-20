<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\Time;
use App\Domain\Time\TimeTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;

class TimeTransformerTest extends TimeTestCase
{
    public function testTransformTimeResource(): void
    {
        $manager = new Manager();

        $baseUrl = 'http://example.com';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));

        $resource = new Item($this->time(), new TimeTransformer(), 'time');

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
