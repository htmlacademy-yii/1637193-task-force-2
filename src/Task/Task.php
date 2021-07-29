<?php

namespace TaskForce\Task;

use TaskForce\Task\StatefulInterface;
use TaskForce\Task\StateMachine\StateMachine;
use TaskForce\Task\StateMachine\CustomerStateMachine;
use TaskForce\Task\StateMachine\ImplementorStateMachine;
use TaskForce\Task\UserRole;

class Task implements StatefulInterface
{

    /**
     * Task constructor.
     * @param TaskStatus $status статус задачи
     * @param int $customerId id клиента, поставившего задачу
     * @param int|null $implementorId id исполнителя задачи (при его наличии) либо null
     */
    public function __construct(
        protected TaskStatus $status,
        public int $customerId,
        public ?int $implementorId = null
    )
    {
    }

    /**
     * @return TaskStatus статус задачи объекта TaskStatus
     */
    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    /**
     * Задает статус задачи объекта TaskStatus.
     * @param TaskStatus $status новый статус
     */
    public function setStatus(TaskStatus $status): void
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
            return \TaskForce\Task\UserRole::customer();
        }

        if ($this->implementorId && $userId === $this->implementorId) {
            //return логика для исполнителя
            return \TaskForce\Task\UserRole::implementor();
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

        if ($role !== null && $role->equals(UserRole::customer())) {
            return new CustomerStateMachine($this);
        }

        if ($role !== null && $role->equals(UserRole::implementor())) {
            return new ImplementorStateMachine($this);
        }

        return null;
    }
}
