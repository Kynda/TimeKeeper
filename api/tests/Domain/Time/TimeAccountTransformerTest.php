<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\TimeAccount;
use App\Domain\Time\TimeAccountTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;

class TimeAccountTransformerTest extends TimeTestCase
{
    public function testTransformTimeAccountResource(): void
    {
        $manager = new Manager();

        $baseUrl = 'http://example.com';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));

        $resource = new Collection(
            [new TimeAccount('DayJob'), new TimeAccount('Personal')],
            new TimeAccountTransformer(),
            'account'
        );

        $this->assertEquals(
            [
                'data' => [
                    [
                        'type' => 'account',
                        'id' => 'DayJob',
                        'attributes' => [
                            'account' => 'DayJob'
                        ],
                        'links' => [
                            'self' => 'http://example.com/account/DayJob'
                        ]
                    ],
                    [
                        'id' => 'Personal',
                        'type' => 'account',
                        'attributes' => [
                            'account' => 'Personal'
                        ],
                        'links' => [
                            'self' => 'http://example.com/account/Personal'
                        ]
                    ],
                ],
            ],
            $manager->createData($resource)->toArray()
        );
    }
}
