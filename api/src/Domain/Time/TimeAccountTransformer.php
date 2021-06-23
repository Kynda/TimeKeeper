<?php

declare(strict_types=1);

namespace App\Domain\Time;

use League\Fractal\TransformerAbstract;

class TimeAccountTransformer extends TransformerAbstract
{
    public function transform(TimeAccount $timeAccount)
    {
        return array_merge(
            $timeAccount->jsonSerialize(),
            ['id' => $timeAccount->getAccount()]
        );
    }
}
