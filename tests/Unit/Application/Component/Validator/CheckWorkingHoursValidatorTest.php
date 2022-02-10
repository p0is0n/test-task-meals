<?php

declare(strict_types=1);

namespace tests\Meals\Unit\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NotWorkingHoursException;
use Meals\Application\Component\Validator\IsWorkingHoursValidator;
use Meals\Domain\Poll\PollWorkingHours;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Assert\Assert;
use \DateTime;
use \DateInterval;
use \DatePeriod;

class CheckWorkingHoursValidatorTest extends TestCase
{
    use ProphecyTrait;

    public function testWorkingHoursSuccessful()
    {
        $pollWorkingHours = $this->prophesize(PollWorkingHours::class);
        $pollWorkingHours->isWorkingHours()->willReturn(true);

        $validator = new IsWorkingHoursValidator();
        verify($validator->validate($pollWorkingHours->reveal()))->null();
    }

    public function testWorkingHoursFailure()
    {
        $this->expectException(NotWorkingHoursException::class);

        $pollWorkingHours = $this->prophesize(PollWorkingHours::class);
        $pollWorkingHours->isWorkingHours()->willReturn(false);

        $validator = new IsWorkingHoursValidator();
        verify($validator->validate($pollWorkingHours->reveal()));
    }

    public function testWorkingHoursDateRangeSuccessful()
    {
        $sDate = new DateTime('2022-02-07 6:00');
        $eDate = new DateTime('2022-02-07 22:00');
        $iDate = DateInterval::createFromDateString('1 hour');

        $dates = new DatePeriod($sDate, $iDate, $eDate);
        $verify = array();

        foreach ($dates as $date) {
            $pollWorkingHours = new PollWorkingHours($date);
            $verify[] = $pollWorkingHours->isWorkingHours();
        }

        $this->assertNotContains(false, $verify);
    }

    public function testWorkingHoursDateRangeFailure()
    {
        $sDate = new DateTime('2022-02-07 0:00');
        $eDate = new DateTime('2022-02-07 5:00');
        $iDate = DateInterval::createFromDateString('1 hour');

        $dates = new DatePeriod($sDate, $iDate, $eDate);
        $verify = array();

        foreach ($dates as $date) {
            $pollWorkingHours = new PollWorkingHours($date);
            $verify[] = $pollWorkingHours->isWorkingHours();
        }

        $this->assertNotContains(true, $verify);
    }

}
