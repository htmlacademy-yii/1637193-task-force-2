<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;

class RespondAction extends TaskAction
{
    public const APPLY_ACTION = 'respond';
    public const ACTION_DESCRIPTION = 'Откликнуться на задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь откликнуться на задачу
     * @param Task $task Объект конкретной задачи
     * @param int $currentUserId Id пользователя
     * @return bool Да\нет
     */
    public function hasRights(Task $task, int $currentUserId): bool
    {
        return $task->implementorId === $currentUserId;
    }
}
