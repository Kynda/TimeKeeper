<?php
declare(strict_types=1);

namespace App\Application\Actions\Account;

use App\Application\Actions\Action;
use App\Domain\Account\AccountService;
use Psr\Log\LoggerInterface;

abstract class AccountAction extends Action
{
    /**
     * @var accountService
     */
    protected $accountService;

    /**
     * @param LoggerInterface $logger
     * @param AccountRepository $AccountRepository
     */
    public function __construct(
        LoggerInterface $logger,
        AccountService $accountService
    ) {
        parent::__construct($logger);
        $this->accountService = $accountService;
    }
}
