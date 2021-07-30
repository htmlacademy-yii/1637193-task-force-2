<?php

namespace TaskForce\Task;

use TaskForce\Task\StatusInterface;
use TaskForce\Task\StateMachine\StateMachine;
use TaskForce\Task\StateMachine\CustomerStateMachine;
use TaskForce\Task\StateMachine\ImplementorStateMachine;
use TaskForce\Task\UserRoleEnum;

class Task implements StatusInterface
{

    /**
     * Task constructor.
     * @param TaskStatusEnum $status статус задачи
     * @param int $customerId id клиента, поставившего задачу
     * @param int|null $implementorId id исполнителя задачи (при его наличии) либо null
     */
    public function __construct(
        protected TaskStatusEnum $status,
        public int $customerId,
        public ?int $implementorId = null
    )
    {
    }

    /**
     * @return TaskStatusEnum статус задачи объекта TaskStatusEnum
     */
    public function getStatus(): TaskStatusEnum
    {
        return $this->status;
    }

    /**
     * Задает статус задачи объекта TaskStatusEnum.
     * @param TaskStatusEnum $status новый статус
     */
    public function setStatus(TaskStatusEnum $status): void
    {
        $this->status = $status;
    }

    /**
     * Определяет по id пользователя, к какой роли он принадлежит
     * @param int $userId id пользователя
     * @return null логика для определенного типа пользователя
     */
    public function getRoleById(int $userId)
    {
        if ($userId === $this->customerId) {
            //return логика для заказчика
            return \TaskForce\Task\UserRoleEnum::customer();
        }

        if ($this->implementorId && $userId === $this->implementorId) {
            //return логика для исполнителя
            return \TaskForce\Task\UserRoleEnum::implementor();
        }

        return null;
    }

    /**
     * Выполняет отслеживаемый сценарий для пользователя согласно его роли по отношению к задаче либо null,
     * если пользователь не имеет отношения к задаче
     * @param int $userId id пользователя
     * @return CustomerStateMachine|null сценарий с переходами действий и статусов
     */
    public function getStatefulTask(int $userId): ?CustomerStateMachine
    {
        $role = $this->getRoleById($userId);

        if ($role?->equals(UserRoleEnum::customer())) {
            return new CustomerStateMachine($this);
        }

        if ($role?->equals(UserRoleEnum::implementor())) {
            return new ImplementorStateMachine($this);
        }

        return null;
    }
}
