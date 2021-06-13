<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\TimeValidationException;
use App\Domain\Time\TimeValidator;
use Tests\TestCase;

class TimeValidatorTest extends TestCase
{
    const
        DATE     = '2021-01-01',
        START    = '13:00',
        END      = '14:00',
        HOURS    = '1.00',
        ACCOUNT  = 'Dayjob',
        TASK     = 'Code Review',
        NOTES    = 'Code review all the things',
        BILLABLE = '1'
    ;

    public function testValidatorNormalizesArgs(): void
    {
        // Requests are entirely strings...
        $request = [
            'date'     => self::DATE,
            'start'    => self::START,
            'end'      => self::END,
            'hours'    => self::HOURS,
            'account'  => self::ACCOUNT,
            'task'     => self::TASK,
            'notes'    => self::NOTES,
            'billable' => self::BILLABLE,
            'extra'    => 'Extra submitted field'
        ];

        // Extra fields removed, values cast to correct types...
        $expected = $request;
        $expected['hours'] = (float)$expected['hours'];
        $expected['billable'] = (bool)$expected['billable'];
        unset($expected['extra']);

        $TimeValidator = new TimeValidator($request);

        $this->assertEquals(
            $expected,
            $TimeValidator->get()
        );
    }

    public function testValidatorRequiresRequiredFields(): void
    {
        try {
            $TimeValidator = new TimeValidator([]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $missingfields = [
                'date',
                'start',
                'end',
                'hours',
                'account',
                'task',
                'notes',
                'billable'
            ];
            array_map(function($missingField) use ($e) {
                $this->assertChainHasException(
                    $missingField,
                    sprintf(
                        TimeValidationException::MISSING_REQUIRED_FIELD,
                        $missingField
                    ),
                    $e
                );
            });
        }
    }

    public function testValidatorValidatesDateParses(): void
    {
        try {
            // Invalid month and day...
            $TimeValidator = new TimeValidator([
                'date' => '2021-13-45'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'date',
                TimeValidationException::INVALID_DATE_FORMAT,
                $e
            );
        }
    }

    public function testValidatorValidatesStartAndEndParses(): void
    {
        try {
            // Start/End should be in 24H time...
            $TimeValidator = new TimeValidator([
                'start' => "01:00 PM",
                'end' => '02:00 PM'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'start',
                TimeValidationException::INVALID_TIME_FORMAT,
                $e
            );
            $this->assertChainHasException(
                'end',
                TimeValidationException::INVALID_TIME_FORMAT,
                $e
            );
        }
    }

    public function testValidatorValidatesEndsAfterStart(): void
    {
        try {
            // Ends before it begins...
            $TimeValidator = new TimeValidator([
                'start' => '14:00',
                'end' => '13:00'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'end',
                TimeValidationException::INVALID_TIME_RANGE,
                $e
            );
        }
    }

    public function testValidateHoursIsGreaterThanZero(): void
    {
        try {
            // Hours is 0
            $TimeValidator = new TimeValidator([
                'hours' => '0'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'hours',
                TimeValidationException::INVALID_HOURS_RANGE,
                $e
            );
        }
    }

    public function testValidateHoursParsesToFloat(): void
    {
        try {
            // Hours is 0
            $TimeValidator = new TimeValidator([
                'hours' => 'Hello'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'hours',
                sprintf(
                    TimeValidationException::INVALID_TYPE,
                    'hours',
                    'float'
                ),
                $e
            );
        }
    }

    public function testValidateHoursIsALie(): void
    {
        try {
            // Hours is 0
            $TimeValidator = new TimeValidator([
                'start' => '13:00',
                'end'   => '14:00',
                'hours' => '8.00'
            ]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'hours',
                TimeValidationException::START_END_HOURS_INCONGRUENT,
                $e
            );
        }
    }

    public function testAccountMayNotContainCommas(): void
    {
        $this->markTestSkipped();
    }

    public function testTaskMayNotContainCommas(): void
    {
        $this->markTestSkipped();
    }

    private function assertChainHasException(string $field, string $message, TimeValidationException $e)
    {
        $hasException = false;
        do {
            $message = sprintf(
                TimeValidationException::MISSING_REQUIRED_FIELD,
                $missingField
            );
            if ($e->getField() === $missingField &&
                $e->getMessage() == $message
            ) {
                $hasException = true;
            }
        } while ($e = $e->getPrevious());
        $this->assertTrue(
            $hasException,
            sprintf(
                'Failed asserting that exception chain contains message "%s" for field "%s"',
                $message,
                $field
            )
        );
    }
}
