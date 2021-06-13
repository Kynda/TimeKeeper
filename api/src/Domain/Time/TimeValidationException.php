<?php
declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\DomainException\DomainValidationException;

class TimeValidationException extends DomainValidationException
{
    const
        INVALID_DATE_FORMAT         = "Date format must conform to ISO8601",
        INVALID_TIME_FORMAT         = "Time format must conform to ISO8601",
        INVALID_TIME_RANGE          = "End must be after Start",
        INVALID_HOURS_RANGE         = "Hours must be greater than 0",
        START_END_HOURS_INCONGRUENT = "The range between Start and End does not match the value of Hours"
    ;
}
