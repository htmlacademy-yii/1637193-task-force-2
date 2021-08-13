<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;

abstract class TaskAction
{
    public const APPLY_ACTION = '';
    public const ACTION_DESCRIPTION = '';
    public ?TaskStatusEnum $transitFromStatus;
    public ?TaskStatusEnum $transitToStatus;

    public function __construct($fromStatus = null, $toStatus = null)
    {
        $this->transitFromStatus = $fromStatus;
        $this->transitToStatus = $toStatus;
    }

    /**
     * Проверяет, имеет ли право указанный пользователь выполнять действия по смене статуса задачи
     * @param Task $task объект конкретной задачи
     * @param int $currentUserId id пользователя
     * @return bool да\нет
     */
    abstract public function hasRights(Task $task, int $currentUserId): bool;
}
