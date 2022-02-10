<?php

declare(strict_types=1);

namespace Meals\Domain\Poll;

use \DateTime;

class PollWorkingHours
{
    private $workingHoursMatch = '(?:1)\-(?:0?[6-9]|1[0-9]|2[0-2])';
    private $workingHoursFormat = 'N-H';

    public function __construct(
        private ?DateTime $nowDateTime = null
    ) {
        if (null === $this->nowDateTime) {
            $this->nowDateTime = new DateTime('now');
        }
    }

    public function isWorkingHours(): bool
    {
        return !! preg_match('/^' . $this->workingHoursMatch . '$/',
            $this->nowDateTime->format($this->workingHoursFormat));
    }

}
