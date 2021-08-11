<?php

namespace TaskForce\Task\Action;

class Refuse extends TaskAction {

    protected string $action = 'refuse';
    protected string $actionDescription = 'Отказаться от задачи';

    /**
     * Проверяет, имеет ли право указанный пользователь отказаться от задачи
     * @param int $implementorId id исполнителя задачи
     * @param int $userId id проверяемого пользователя
     * @param int $customerId id заказчика задачи
     * @param string $status статус задачи на текущий момент
     * @return bool да\нет
     */
    protected function hasRights(int $implementorId, int $userId, int $customerId, string $status): bool
    {
        return $implementorId === $userId && $status === 'in_progress';
    }
}
