<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;

class RefuseAction extends TaskAction
{
    public const APPLY_ACTION = 'refuse';
    public const ACTION_DESCRIPTION = 'Отказаться от задачи';

    /**
     * Проверяет, имеет ли право указанный пользователь отказаться от задачи
     * @param Task $task Объект конкретной задачи
     * @param int $currentUserId Id пользователя
     * @return bool Да\нет
     */
    public function hasRights(Task $task, int $currentUserId): bool
    {
        return $task->implementorId === $currentUserId;
    }
}
