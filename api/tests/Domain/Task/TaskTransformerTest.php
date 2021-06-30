<?php

declare(strict_types=1);

namespace Tests\Domain\Task;

use App\Domain\Task\Task;
use App\Domain\Task\TaskTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Tests\TestCase;

class TaskTransformerTest extends TestCase
{
    public function testTransformTaskResource(): void
    {
        $manager = new Manager();

        $baseUrl = 'http://example.com';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));

        $resource = new Collection(
            [new Task('Shopping'), new Task('Chores')],
            new TaskTransformer(),
            'task'
        );

        $this->assertEquals(
            [
                'data' => [
                    [
                        'type' => 'task',
                        'id' => 'Shopping',
                        'attributes' => [
                            'task' => 'Shopping'
                        ],
                        'links' => [
                            'self' => 'http://example.com/task/Shopping'
                        ]
                    ],
                    [
                        'id' => 'Chores',
                        'type' => 'task',
                        'attributes' => [
                            'task' => 'Chores'
                        ],
                        'links' => [
                            'self' => 'http://example.com/task/Chores'
                        ]
                    ],
                ],
            ],
            $manager->createData($resource)->toArray()
        );
    }
}
