<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;

class CompleteAction extends TaskAction
{
    public const APPLY_ACTION = 'complete';
    public const ACTION_DESCRIPTION = 'Завершить задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь завершить задачу
     * @param Task $task Объект конкретной задачи
     * @param int $currentUserId Id пользователя
     * @return bool Да\нет
     */
    public function hasRights(Task $task, int $currentUserId): bool
    {
        return $task->customerId === $currentUserId;
    }
}
