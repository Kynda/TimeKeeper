<?php

declare(strict_types=1);

namespace App\Domain\Time;

use JsonSerializable;

class User implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string
     */
    private $date;

    /**
     * @var string
     */
    private $start;

    /**
     * @var string
     */
    private $end;

    /**
     * @var float
     */
    private $hours;

    /**
     * @var string
     */
    private $account;

    /**
     * @var string
     */
    private $task;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var bool
     */
    private $billable;

    /**
     * __construct
     *
     * @param ?int $id
     * @param string $date
     * @param string $start
     * @param string $end
     * @param float $hours
     * @param string $account
     * @param string $task
     * @param string $notes
     */
    public function __construct(
        ?int $id,
        string $date,
        string $start,
        string $end,
        float $hours,
        string $account,
        string $task,
        string $notes,
        bool $billable
    ) {
        $this->id      = $id;
        $this->date    = $date;
        $this->start   = $start;
        $this->end     = $end;
        $this->hours   = $hours;
        $this->account = $account;
        $this->task    = $task;
        $this->notes   = $notes;
        $this->billable = $billable;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDate(): string
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getStart(): string
    {
        return $this->start;
    }

    /**
     * @return string
     */
    public function getEnd(): string
    {
        return $this->end;
    }

    /**
     * @return float
     */
    public function getHours(): float
    {
        return $this->hours;
    }

    /**
     * @return string
     */
    public function getAccount(): string
    {
        return $this->account;
    }

    /**
     * @return string
     */
    public function getTask(): string
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getNotes(): string
    {
        return $this->notes;
    }

    /**
     * @return bool
     */
    public function getBillable(): bool
    {
        return $this->billable;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id'       => $this->getId(),
            'date'     => $this->getDate(),
            'end'      => $this->getEnd(),
            'start'    => $this->getStart(),
            'hours'    => $this->getHours(),
            'account'  => $this->getAccount(),
            'task'     => $this->getTask(),
            'notes'    => $this->getNotes(),
            'billable' => $this->getBillable(),
        ];
    }
}
