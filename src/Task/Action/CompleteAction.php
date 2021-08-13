<?php

namespace TaskForce\Task\Action;

use JetBrains\PhpStorm\ArrayShape;
use TaskForce\Task\Task;
use TaskForce\Task\TaskStatusEnum;

class CompleteAction extends TaskAction
{
    public const APPLY_ACTION = 'complete';
    public const ACTION_DESCRIPTION = 'Завершить задачу';

    #[ArrayShape([self::APPLY_ACTION => "string"])] public static function getAction(): array
    {
        return [self::APPLY_ACTION => self::ACTION_DESCRIPTION];
    }

    /**
     * Проверяет, имеет ли право указанный пользователь завершить задачу
     * @param Task $task объект конкретной задачи
     * @param int $currentUserId id пользователя
     * @return bool да\нет
     */
    protected function hasRights(Task $task, int $currentUserId): bool
    {
        return ($task->customerId === $currentUserId) && ($task->getStatus() === TaskStatusEnum::in_progress());
    }
}
