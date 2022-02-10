<?php

declare(strict_types=1);

namespace tests\Meals\Functional\Interactor;

use Meals\Application\Feature\Dish\UseCase\EmployeeSelectDishFromActivePoll\Interactor;
use Meals\Domain\Dish\DishList;
use Meals\Domain\Dish\Dish;
use Meals\Domain\Employee\Employee;
use Meals\Domain\Menu\Menu;
use Meals\Domain\Poll\Poll;
use Meals\Domain\Poll\PollResult;
use Meals\Domain\Poll\PollWorkingHours;
use Meals\Domain\User\Permission\Permission;
use Meals\Domain\User\Permission\PermissionList;
use Meals\Domain\User\User;
use Meals\Application\Component\Validator\IsWorkingHoursValidator;
use tests\Meals\Functional\Fake\Provider\FakeEmployeeProvider;
use tests\Meals\Functional\Fake\Provider\FakePollProvider;
use tests\Meals\Functional\FunctionalTestCase;
use \DateTime;

class EmployeeSelectDishFromActivePollTest extends FunctionalTestCase
{
    public function testSuccessful()
    {
        $poll = $this->getPoll();
        $employee = $this->getEmployee();
        $pollWorkingHours = $this->getPollWorkingHours();
        $pollResult = $this->performTestMethod($employee, $poll, $pollWorkingHours);
        verify($pollResult)->equals($pollResult);
    }

    public function testPollResultByEmployee()
    {
        $poll = $this->getPoll();
        $employee = $this->getEmployee();
        $pollWorkingHours = $this->getPollWorkingHours();
        $pollResult = $this->performTestMethod($employee, $poll, $pollWorkingHours);
        verify($pollResult->getEmployee())->equals($employee);
    }

    public function testPollResultByPoll()
    {
        $poll = $this->getPoll();
        $employee = $this->getEmployee();
        $pollWorkingHours = $this->getPollWorkingHours();
        $pollResult = $this->performTestMethod($employee, $poll, $pollWorkingHours);
        verify($pollResult->getPoll())->equals($poll);
    }

    public function testPollResultByDishId()
    {
        $poll = $this->getPoll();
        $employee = $this->getEmployee();
        $pollWorkingHours = $this->getPollWorkingHours();
        $pollResult = $this->performTestMethodWithDish($employee, $poll, $pollWorkingHours, 11);
        verify($pollResult->getDish()->getId())->equals(11);
    }

    private function performTestMethod(Employee $employee, Poll $poll, PollWorkingHours $pollWorkingHours): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakePollProvider::class)->setPollWorkingHours($pollWorkingHours);

        return $this->getContainer()->get(Interactor::class)->selectDishFromActivePoll($employee->getId(), $poll->getId(), current($poll->getMenu()->getDishes()->getDishes())->getId());
    }

    private function performTestMethodWithDish(Employee $employee, Poll $poll, PollWorkingHours $pollWorkingHours, int $dishId): PollResult
    {
        $this->getContainer()->get(FakeEmployeeProvider::class)->setEmployee($employee);
        $this->getContainer()->get(FakePollProvider::class)->setPoll($poll);
        $this->getContainer()->get(FakePollProvider::class)->setPollWorkingHours($pollWorkingHours);

        return $this->getContainer()->get(Interactor::class)->selectDishFromActivePoll($employee->getId(), $poll->getId(), $poll->getMenu()->getDishes()->getDishById($dishId)->getId());
    }

    private function getPollWorkingHours(): PollWorkingHours
    {
        return new PollWorkingHours(
            new DateTime('2022-02-07 10:00:00')
        );
    }

    private function getEmployee(): Employee
    {
        return new Employee(
            1,
            $this->getUserWithPermissions(),
            4,
            'Surname'
        );
    }

    private function getUserWithPermissions(): User
    {
        return new User(
            1,
            new PermissionList(
                [
                    new Permission(Permission::VIEW_ACTIVE_POLLS),
                ]
            ),
        );
    }

    private function getPoll(): Poll
    {
        return new Poll(
            1,
            true,
            new Menu(
                1,
                'title',
                new DishList([
                    new Dish(
                        10,
                        'title',
                        'description'
                    ),
                    new Dish(
                        11,
                        'title',
                        'description'
                    )
                ]),
            )
        );
    }
}
