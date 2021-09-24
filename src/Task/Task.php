<?php

namespace TaskForce\Task;

use TaskForce\Task\Exceptions\TaskActionException;
use TaskForce\Task\StateMachine\StateMachine;
use TaskForce\Task\StateMachine\CustomerStateMachine;
use TaskForce\Task\StateMachine\ImplementorStateMachine;
use TaskForce\Task\UserRoleEnum;

class Task implements StatusInterface
{
    /**
     * Task constructor.
     * @param TaskStatusEnum $status статус задачи
     * @param int $customerId Id клиента, поставившего задачу
     * @param int|null $implementorId Id исполнителя задачи (при его наличии) либо null
     */
    public function __construct(
        protected TaskStatusEnum $status,
        public int $customerId,
        public ?int $implementorId = null
    ) {
    }

    /**
     * @return TaskStatusEnum Статус задачи объекта TaskStatusEnum
     */
    public function getStatus(): TaskStatusEnum
    {
        return $this->status;
    }

    /**
     * Задает статус задачи объекта TaskStatusEnum.
     * @param TaskStatusEnum $status Новый статус
     */
    public function setStatus(TaskStatusEnum $status): void
    {
        $this->status = $status;
    }

    /**
     * Определяет по id пользователя, к какой роли он принадлежит
     * @param int $userId Id пользователя
     * @return UserRoleEnum Логика для определенного типа пользователя
     * @throws TaskActionException Исключение на случай невозможности определить роль пользователя
     */
    public function getRoleById(int $userId): UserRoleEnum
    {
        if ($userId === $this->customerId) {
            return UserRoleEnum::customer();
        }

        if ($this->implementorId && $userId === $this->implementorId) {
            return UserRoleEnum::implementor();
        }

        throw new TaskActionException('Роль пользователя не определена');
    }

    /**
     * Выполняет отслеживаемый сценарий для пользователя согласно его роли по отношению к задаче
     * либо null, если пользователь не имеет отношения к задаче
     * @param int $userId Id пользователя
     * @return StateMachine|null Сценарий с переходами действий и статусов для конкретной роли заказчика\исполнителя
     * @throws TaskActionException Исключение на случай невозможности определить роль пользователя
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
