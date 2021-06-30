<?php

declare(strict_types=1);

namespace App\Domain\Time;

use League\Fractal\TransformerAbstract;

class TimeTransformer extends TransformerAbstract
{
    /**
     * @param Time $time
     * @return array
     */
    public function transform(Time $time)
    {
        return $time->jsonSerialize();
    }
}
