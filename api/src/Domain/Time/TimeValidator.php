<?php

declare(strict_types=1);

namespace App\Domain\Time;

class TimeValidator
{
    private $args;

    public function __construct(array $args)
    {
        $this->args = $args;
    }

    public function get()
    {
        return $this
            ->validate()
            ->filter()
            ->expose();
    }

    private function filter(): TimeValidator
    {
        return $this;
    }

    private function validate(): TimeValidator
    {
        return $this;
    }

    private function expose(): array
    {
        return $this->args;
    }
}
