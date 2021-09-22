<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;

class CancelAction extends TaskAction
{
    public const APPLY_ACTION = 'cancel';
    public const ACTION_DESCRIPTION = 'Отменить задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь отменить задачу
     * @param Task $task Объект конкретной задачи
     * @param int $currentUserId Id пользователя
     * @return bool Да\нет
     */
    public function hasRights(Task $task, int $currentUserId): bool
    {
        return $task->customerId === $currentUserId;
    }
}
