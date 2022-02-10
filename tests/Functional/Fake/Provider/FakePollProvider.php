<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Fake\Provider;

use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollList;
use Meals\Domain\Poll\PollWorkingHours;

class FakePollProvider implements PollProviderInterface
{
    private Poll $poll;

    private PollList $polls;

    private PollWorkingHours $pollWorkingHours;

    public function getActivePolls(): PollList
    {
        return $this->polls;
    }

    public function getPoll(int $pollId): Poll
    {
        return $this->poll;
    }

    public function getPollWorkingHours(): PollWorkingHours
    {
        return $this->pollWorkingHours;
    }

    public function setPoll(Poll $poll): void
    {
        $this->poll = $poll;
    }

    public function setPolls(PollList $polls): void
    {
        $this->polls = $polls;
    }

    public function setPollWorkingHours(PollWorkingHours $pollWorkingHours): void
    {
        $this->pollWorkingHours = $pollWorkingHours;
    }
}
