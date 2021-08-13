<?php

namespace TaskForce\Task;

interface StatusInterface
{
    /**
     * Возвращает статус задачи объекта TaskStatusEnum.
     * @return TaskStatusEnum
     */
    public function getStatus(): TaskStatusEnum;

    /**
     * Задает статус задачи объекта TaskStatusEnum.
     * @param TaskStatusEnum $status новый статус
     */
    public function setStatus(TaskStatusEnum $status): void;
}
