<?php

declare(strict_types=1);

namespace Meals\Application\Component\Validator;

use Meals\Application\Component\Validator\Exception\NotWorkingHoursException;
use Meals\Domain\Poll\PollWorkingHours;

class IsWorkingHoursValidator
{

    public function validate(PollWorkingHours $pollWorkingHours): void
    {
    	if (! $pollWorkingHours->isWorkingHours()) {
        	throw new NotWorkingHoursException();
        }
    }
}
