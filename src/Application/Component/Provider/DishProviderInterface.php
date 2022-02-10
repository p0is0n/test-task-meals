<?php

declare(strict_types=1);

namespace Meals\Application\Component\Provider;

use Meals\Domain\Poll\PollResult;

interface DishProviderInterface
{
    public function selectDishFromActivePoll(int $employeeId, int $pollId, int $dishId): PollResult;
}
