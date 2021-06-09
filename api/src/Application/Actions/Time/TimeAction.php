<?php

declare(strict_types=1);

namespace App\Application\Actions\Time;

use App\Application\Actions\Action;
use App\Domain\Time\TimeRepository;
use App\Domain\Time\TimeSerializer;
use Psr\Log\LoggerInterface;
use Psr\Http\Message\ResponseInterface;

class TimeAction extends Action
{
    /**
     * @var TimeRepository
     */
    private $timeRepository;

    public function __construct(LoggerInterface $logger, TimeRepository $timeRepository)
    {
        parent::__construct($logger);
        $this->timeRepository = $timeRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        $id = (int)$this->resolveArg('id');
        $time = $this->timeRepository->time($id);

        return $this->respondWithData(new TimeSerializer($time));
    }
}
