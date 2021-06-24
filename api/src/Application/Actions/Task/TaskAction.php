<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Application\Actions\Action;
use App\Domain\Task\TaskService;
use Psr\Log\LoggerInterface;

abstract class TaskAction extends Action
{
    /**
     * @var taskService
     */
    protected $taskService;

    /**
     * @param LoggerInterface $logger
     * @param TaskRepository $TaskRepository
     */
    public function __construct(
        LoggerInterface $logger,
        TaskService $taskService
    ) {
        parent::__construct($logger);
        $this->taskService = $taskService;
    }
}
