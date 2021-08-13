<?php

namespace TaskForce\Task;

use TaskForce\Task\Exceptions\AppException;
use TaskForce\Task\StateMachine\StateMachine;
use TaskForce\Task\StateMachine\CustomerStateMachine;
use TaskForce\Task\StateMachine\ImplementorStateMachine;

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
     * @return \TaskForce\Task\UserRoleEnum логика для определенного типа пользователя
     * @throws AppException
     */
    public function getRoleById(int $userId): UserRoleEnum
    {
        if ($userId === $this->customerId) {
            return UserRoleEnum::customer();
        }

        if ($this->implementorId && $userId === $this->implementorId) {
            return UserRoleEnum::implementor();
        }

        throw new AppException('Undefined user role');
    }

    /**
     * Выполняет отслеживаемый сценарий для пользователя согласно его роли по отношению к задаче
     * либо null, если пользователь не имеет отношения к задаче
     * @param int $userId id пользователя
     * @return StateMachine сценарий с переходами действий и статусов
     * @throws AppException
     */
    public function getStatefulTask(int $userId): StateMachine
    {
        $role = $this->getRoleById($userId);

        if ($role->equals(UserRoleEnum::customer())) {
            return new CustomerStateMachine($this);
        }

        if ($role->equals(UserRoleEnum::implementor())) {
            return new ImplementorStateMachine($this);
        }

        throw new AppException('Unknown state machine because user role is undefined');
    }
}
