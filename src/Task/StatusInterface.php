<?php

namespace TaskForce\Task;

use TaskForce\Task\TaskStatusEnum;

interface StatusInterface
{
    /**
     * Возвращает статус задачи объекта TaskStatusEnum.
     * @return TaskStatusEnum Текущий статус
     */
    public function getStatus(): TaskStatusEnum;

    /**
     * Задает статус задачи объекта TaskStatusEnum.
     * @param TaskStatusEnum $status Новый статус
     */
    public function setStatus(TaskStatusEnum $status): void;
}
