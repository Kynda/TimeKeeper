<?php
declare(strict_types=1);

namespace App\Application\Actions\Time;

use App\Application\Actions\Action;
use App\Domain\Time\TimeService;
use Psr\Log\LoggerInterface;

abstract class TimeAction extends Action
{
    /**
     * @var timeRepository
     */
    protected $timeService;

    /**
     * @param LoggerInterface $logger
     * @param TimeRepository $timeRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TimeService $timeService
    ) {
        parent::__construct($logger);
        $this->timeService = $timeService;
    }
}
