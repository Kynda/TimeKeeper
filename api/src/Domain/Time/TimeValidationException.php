<?php
declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\DomainException\DomainValidationException;

class TimeValidationException extends DomainValidationException
{
    public $message = 'The time record requested does not exist.';
}
