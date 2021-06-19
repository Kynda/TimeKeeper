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

        $this
            ->validateDateParses()
            ->validateStartAndEndParses()
            ->validateEndsAfterStart()
            ->validateHoursIsGreaterThanZero()
            ->validateHoursParsesToFloat()
            ->validateHoursIntervalIsCorrect()
            ->validateAccountMayNotContainCommas()
            ->validateTaskMayNotContainCommas();

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

    private function validateDateParses(): self
    {
        if (!\DateTimeImmutable::createFromFormat('Y-m-d', $this->args['date'])) {
            $this->addError(
                TimeValidationException::INVALID_DATE_FORMAT,
                'date'
            );
        }

        return $this;
    }

    private function validateStartAndEndParses(): self
    {
        if (!\DateTimeImmutable::createFromFormat('H:i', $this->args['start'])) {
            $this->addError(
                TimeValidationException::INVALID_TIME_FORMAT,
                'start'
            );
        }

        if (!\DateTimeImmutable::createFromFormat('H:i', $this->args['end'])) {
            $this->addError(
                TimeValidationException::INVALID_TIME_FORMAT,
                'end'
            );
        }

        return $this;
    }

    private function validateEndsAfterStart(): self
    {
        $start = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $this->args['date'] . ' ' . $this->args['start']
        );

        $end = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $this->args['date'] . ' ' . $this->args['end']
        );

        if ($end < $start) {
            $this->addError(
                TimeValidationException::INVALID_TIME_RANGE,
                'end'
            );
        }

        return $this;
    }

    private function validateHoursIsGreaterThanZero(): self
    {
        if ($this->args['hours'] <= 0) {
            $this->addError(
                TimeValidationException::INVALID_HOURS_RANGE,
                'hours'
            );
        }
        return $this;
    }

    private function validateHoursParsesToFloat(): self
    {
        if (!is_numeric($this->args['hours'])) {
            $this->addError(
                sprintf(
                    TimeValidationException::INVALID_TYPE,
                    'hours',
                    'float'
                ),
                'hours'
            );
        }

        return $this;
    }

    private function validateHoursIntervalIsCorrect(): self
    {
        $start = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $this->args['date'] . ' ' . $this->args['start']
        );

        $end = \DateTimeImmutable::createFromFormat(
            'Y-m-d H:i',
            $this->args['date'] . ' ' . $this->args['end']
        );

        if (!$start || !$end) {
            return $this;
        }

        $diff = $start->diff($end);
        $hours = $diff->format('%h') + (float)$diff->format('%i') /  60;

        if ($hours !== (float)$this->args['hours']) {
            $this->addError(
                TimeValidationException::START_END_HOURS_INCONGRUENT,
                'hours'
            );
        }

        return $this;
    }

    public function validateAccountMayNotContainCommas(): self
    {
        if (false !== strpos($this->args['account'], ",")) {
            $this->addError(
                sprintf(
                    TimeValidationException::COMMAS_NOT_ALLOWED,
                    'account'
                ),
                'account'
            );
        }

        return $this;
    }

    public function validateTaskMayNotContainCommas(): self
    {
        if (false !== strpos($this->args['task'], ",")) {
            $this->addError(
                sprintf(
                    TimeValidationException::COMMAS_NOT_ALLOWED,
                    'task'
                ),
                'task'
            );
        }

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
