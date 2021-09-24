<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;

abstract class TaskAction
{
    public const APPLY_ACTION = '';
    public const ACTION_DESCRIPTION = '';
    public TaskStatusEnum $transitFromStatus;
    public TaskStatusEnum $transitToStatus;

    /**
     * @param TaskStatusEnum $fromStatus Статус задачи, из которого совершается переход
     * @param TaskStatusEnum $toStatus Статус задачи, в который совершается переход
     */
    public function __construct(TaskStatusEnum $fromStatus, TaskStatusEnum $toStatus)
    {
        $this->transitFromStatus = $fromStatus;
        $this->transitToStatus = $toStatus;
    }

    /**
     * Проверяет, имеет ли право указанный пользователь выполнять действия по смене статуса задачи
     * @param Task $task Объект конкретной задачи
     * @param int $currentUserId Id пользователя
     * @return bool Да\нет
     */
    abstract public function hasRights(Task $task, int $currentUserId): bool;
}
