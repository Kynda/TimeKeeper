<?php

declare(strict_types=1);

namespace App\Application\Actions\Account;

use Psr\Http\Message\ResponseInterface as Response;

class ListAccountAction extends AccountAction
{
    /**
     * {@inheritdoc}
     */
    public function action(): Response
    {
        return $this->respondWithResource(
            $this->accountService->collectAccounts()
        );
    }
}
