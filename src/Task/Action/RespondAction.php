<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;

class RespondAction extends TaskAction
{
    public const APPLY_ACTION = 'respond';
    public const ACTION_DESCRIPTION = 'Откликнуться на задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь откликнуться на задачу
     * @param Task $task объект конкретной задачи
     * @param int $currentUserId id пользователя
     * @return bool да\нет
     */
    protected function hasRights(Task $task, int $currentUserId): bool
    {
        return ($task->implementorId === $currentUserId) && ($task->getStatus() === TaskStatusEnum::new());
    }
}
