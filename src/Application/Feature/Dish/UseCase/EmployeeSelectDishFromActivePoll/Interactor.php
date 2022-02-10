<?php

declare(strict_types=1);

namespace Meals\Application\Feature\Dish\UseCase\EmployeeSelectDishFromActivePoll;

use Meals\Application\Component\Provider\EmployeeProviderInterface;
use Meals\Application\Component\Provider\PollProviderInterface;
use Meals\Application\Component\Validator\PollIsActiveValidator;
use Meals\Application\Component\Validator\UserHasAccessToViewPollsValidator;
use Meals\Application\Component\Validator\IsWorkingHoursValidator;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;

class Interactor
{
    public function __construct(
        private EmployeeProviderInterface $employeeProvider,
        private PollProviderInterface $pollProvider,
        private UserHasAccessToViewPollsValidator $userHasAccessToPollsValidator,
        private PollIsActiveValidator $pollIsActiveValidator,
        private IsWorkingHoursValidator $isWorkingHoursValidator
    ) {}

    public function selectDishFromActivePoll(int $employeeId, int $pollId, int $dishId): PollResult
    {
        $pollWorkingHours = $this->pollProvider->getPollWorkingHours();
        $this->isWorkingHoursValidator->validate($pollWorkingHours);

        $employee = $this->employeeProvider->getEmployee($employeeId);
        $poll = $this->pollProvider->getPoll($pollId);

        $this->userHasAccessToPollsValidator->validate($employee->getUser());

        $this->pollIsActiveValidator->validate($poll);

        $dish = $poll->getMenu()->getDishes()->getDishById($dishId);

        $pollResult = new PollResult(
            $poll->getId(),
            $poll,
            $employee,
            $dish,
            $employee->getFloor()
        );

        return $pollResult;
    }
}
