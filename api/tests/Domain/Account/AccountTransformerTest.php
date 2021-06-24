<?php

declare(strict_types=1);

namespace Tests\Domain\Account;

use App\Domain\Account\Account;
use App\Domain\Account\AccountTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Tests\TestCase;

class AccountTransformerTest extends TestCase
{
    public function testTransformAccountResource(): void
    {
        $manager = new Manager();

        $baseUrl = 'http://example.com';
        $manager->setSerializer(new JsonApiSerializer($baseUrl));

        $resource = new Collection(
            [new Account('DayJob'), new Account('Personal')],
            new AccountTransformer(),
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
