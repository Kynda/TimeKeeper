<?php

declare(strict_types=1);

namespace App\Domain\Time;

use League\Fractal\TransformerAbstract;

class TimeTransformer extends TransformerAbstract
{
    public function transform(Time $time)
    {
        return $time->jsonSerialize();
    }
}
