<?php

namespace TaskForce\Task;

use TaskForce\Task\TaskStatus;

interface StatefulInterface
{
    /**
     * Возвращает статус задачи объекта TaskStatus.
     * @return TaskStatus
     */
    public function getFiniteState(): TaskStatus;

    /**
     * Задает статус задачи объекта TaskStatus.
     * @param TaskStatus $state новый статус
     */
    public function setFiniteState(TaskStatus $state): void;
}
