<?php
declare(strict_types=1);

namespace App\Domain\Time;

use App\Domain\DomainException\DomainRecordNotFoundException;

class TimeNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The time you requested does not exist.';
}
