<?php

declare(strict_types=1);

namespace Tests\Domain\Time;

use App\Domain\Time\TimeValidationException;
use App\Domain\Time\TimeValidator;

class TimeValidatorTest extends TimeTestCase
{
    public function testValidatorNormalizesArgs(): void
    {
        // Requests are entirely strings...
        $request = $this->request;

        // Extra fields removed, values cast to correct types...
        $expected = $request;
        $expected['account'] = trim($expected['account']);
        $expected['task'] = trim($expected['task']);
        $expected['hours'] = (float)$expected['hours'];
        $expected['billable'] = (bool)$expected['billable'];
        unset($expected['extra']);

        $TimeValidator = new TimeValidator($request);

        $this->assertEquals(
            $expected,
            $TimeValidator->get()
        );
    }

    /**
     * @dataProvider requiredFieldProvider
     */
    public function testValidatorRequiresRequiredFields($missingField): void
    {
        try {
            $TimeValidator = new TimeValidator([]);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                $missingField,
                sprintf(
                    TimeValidationException::MISSING_REQUIRED_FIELD,
                    $missingField
                ),
                $e
            );
        }
    }

    public function requiredFieldProvider(): array
    {
        return [
                ['date'],
                ['start'],
                ['end'],
                ['hours'],
                ['account'],
                ['task'],
                ['notes'],
                ['billable'],
            ];
    }

    public function testValidatorValidatesDateParses(): void
    {
        $request = $this->request;
        $request['date'] = 'January 01, 2022';

        try {
            // Invalid month and day...
            $TimeValidator = new TimeValidator($request);
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
        $request = $this->request;
        $request['start'] = '01:00 PM';
        $request['end'] = '02:00 PM';

        try {
            // Start/End should be in 24H time...
            $TimeValidator = new TimeValidator($request);;
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
        $request = $this->request;
        $request['start'] = '14:00';
        $request['end'] = '13:00';

        try {
            // Ends before it begins...
            $TimeValidator = new TimeValidator($request);
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
        $request = $this->request;
        $request['hours'] = '0';

        try {
            // Hours is 0
            $TimeValidator = new TimeValidator($request);
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
        $request = $this->request;
        $request['hours'] = 'Hello';

        try {
            $TimeValidator = new TimeValidator($request);
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
        $request = $this->request;
        $request ['start'] = '13:00';
        $request['end'] = '14:00';
        $request['hours']= '8.00';

        try {
            $TimeValidator = new TimeValidator($request);
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
        $request = $this->request;
        $request['account'] = 'Personal,Chores';

        try {
            $TimeValidator = new TimeValidator($request);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'account',
                sprintf(
                    TimeValidationException::COMMAS_NOT_ALLOWED,
                    'account'
                ),
                $e
            );
        }
    }

    public function testTaskMayNotContainCommas(): void
    {
        $request = $this->request;
        $request['task'] = 'Dishes,Laundry';

        try {
            $TimeValidator = new TimeValidator($request);
            $TimeValidator->get();
        } catch (TimeValidationException $e) {
            $this->assertChainHasException(
                'task',
                sprintf(
                    TimeValidationException::COMMAS_NOT_ALLOWED,
                    'task'
                ),
                $e
            );
        }
    }

    private function assertChainHasException(string $field, string $message, TimeValidationException $e)
    {
        $hasException = false;
        do {
            $message = sprintf(
                $message,
                $field
            );
            if ($e->getField() === $field &&
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
