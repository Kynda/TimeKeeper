<?php
declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TimeRecordNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The time record requested does not exist.';
}
