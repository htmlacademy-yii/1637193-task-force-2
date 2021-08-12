<?php

namespace TaskForce\Task\Action;

use TaskForce\Task\Task;

abstract class TaskAction
{
    public const APPLY_ACTION = '';
    public const ACTION_DESCRIPTION = '';

    public function __construct()
    {
        [self::APPLY_ACTION => self::ACTION_DESCRIPTION];
    }

    abstract public static function getAction(): array;

    /**
     * Проверяет, имеет ли право указанный пользователь выполнять действия по смене статуса задачи
     * @param Task $task объект конкретной задачи
     * @param int $currentUserId id пользователя
     * @return bool да\нет
     */
    abstract protected function hasRights(Task $task, int $currentUserId): bool;
}
