<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;

class CancelAction extends TaskAction
{
    public const APPLY_ACTION = 'cancel';
    public const ACTION_DESCRIPTION = 'Отменить задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь отменить задачу
     * @param Task $task объект конкретной задачи
     * @param int $currentUserId id пользователя
     * @return bool да\нет
     */
    public function hasRights(Task $task, int $currentUserId): bool
    {
        return ($task->customerId === $currentUserId) && ($task->getStatus() === TaskStatusEnum::new());
    }
}
