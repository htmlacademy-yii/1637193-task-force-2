<?php

namespace TaskForce\Task;

use TaskForce\Task\TaskStatus;

interface StatefulInterface
{
    /**
     * Возвращает статус задачи объекта TaskStatus.
     * @return TaskStatus
     */
    public function getStatus(): TaskStatus;

    /**
     * Задает статус задачи объекта TaskStatus.
     * @param TaskStatus $status новый статус
     */
    public function setStatus(TaskStatus $status): void;
}
