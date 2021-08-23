<?php

namespace TaskForce\Task;

use TaskForce\Task\Exceptions\AppException;
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
     * @return UserRoleEnum логика для определенного типа пользователя
     * @throws AppException исключение на случай невозможности определить роль пользователя
     */
    public function getRoleById(int $userId): UserRoleEnum
    {
        if ($userId === $this->customerId) {
            return UserRoleEnum::customer();
        }

        if ($this->implementorId && $userId === $this->implementorId) {
            return UserRoleEnum::implementor();
        }

        throw new AppException('Роль пользователя не определена');
    }

    /**
     * Выполняет отслеживаемый сценарий для пользователя согласно его роли по отношению к задаче
     * либо null, если пользователь не имеет отношения к задаче
     * @param int $userId id пользователя
     * @return StateMachine|null сценарий с переходами действий и статусов для конкретной роли заказчика\исполнителя
     * @throws AppException
     */
    public function getStatefulTask(int $userId): ?StateMachine
    {
        $role = $this->getRoleById($userId);

        if ($role->equals(UserRoleEnum::customer())) {
            return new CustomerStateMachine($this);
        }

        if ($role->equals(UserRoleEnum::implementor())) {
            return new ImplementorStateMachine($this);
        }

        return null;
    }
}
