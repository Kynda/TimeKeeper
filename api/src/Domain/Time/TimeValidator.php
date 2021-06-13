<?php

declare(strict_types=1);

namespace App\Domain\Time;

class TimeValidator
{
    /**
     * @var $args
     */
    private $args;

    /**
     * @var TimeValidationException|null
     */
    private $timeValidationException;

    /**
     * @param array $args
     */
    public function __construct(array $args)
    {
        $this->args = $args;
    }

    /**
     * Return a validated and normalized form of the arguments
     *
     * @return array
     */
    public function get(): array
    {
        return $this
            ->validate()
            ->filter()
            ->expose();
    }

    /**
     * Normalize the internal argument array
     *
     * @return self
     */
    private function filter(): self
    {
        $this->args = [
            'date'     => $this->args['date'],
            'start'    => $this->args['start'],
            'end'      => $this->args['end'],
            'hours'    => (float)$this->args['hours'],
            'account'  => $this->args['account'],
            'task'     => $this->args['task'],
            'notes'    => $this->args['notes'],
            'billable' => (bool)$this->args['billable']
        ];

        return $this;
    }

    /**
     * Run all validation against the internal array
     *
     * @return self
     */
    private function validate(): self
    {
        $this->validateRequiredFields();

        if ($this->timeValidationException !== null) {
            throw $this->timeValidationException;
        }

        return $this;
    }

    /**
     * Get the internal array
     *
     * @return array
     */
    private function expose(): array
    {
        return $this->args;
    }

    /**
     * Validate that all required keys are present
     *
     * @return self
     */
    private function validateRequiredFields(): self
    {
        $requiredKeys = [
            'date',
            'start',
            'end',
            'hours',
            'account',
            'task',
            'notes',
            'billable'
        ];

        array_map(function($key) {
            if (!array_key_exists($key, $this->args)) {
                $this->addError(
                    sprintf(
                        TimeValidationException::MISSING_REQUIRED_FIELD,
                        $key
                    ),
                    $key,
                );
            }
        }, $requiredKeys);

        return $this;
    }

    /**
     * Chain DomainValidationExceptions to throw once validation completes
     *
     * @param string $message
     * @param ?string $field
     */
    private function addError(string $message, ?string $field = null)
    {
        $timeValidationException = new TimeValidationException(
            $message,
            $field,
            $this->timeValidationException
        );

        $this->timeValidationException = $timeValidationException;
    }
}
