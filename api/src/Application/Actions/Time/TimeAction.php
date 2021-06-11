<?php
declare(strict_types=1);

namespace App\Application\Actions\Time;

use App\Application\Actions\Action;
use App\Domain\Time\TimeRepository;
use Psr\Log\LoggerInterface;

abstract class TimeAction extends Action
{
    /**
     * @var timeRepository
     */
    protected $timeRepository;

    /**
     * @param LoggerInterface $logger
     * @param TimeRepository $timeRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TimeRepository $timeRepository
    ) {
        parent::__construct($logger);
        $this->timeRepository = $timeRepository;
    }
}
