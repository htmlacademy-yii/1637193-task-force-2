<?php

namespace TaskForce\Task\Action;

class Complete extends TaskAction {

    protected string $action = 'complete';
    protected string $actionDescription = 'Завершить задачу';

    /**
     * Проверяет, имеет ли право указанный пользователь завершить задачу
     * @param int $implementorId id исполнителя задачи
     * @param int $userId id проверяемого пользователя
     * @param int $customerId id заказчика задачи
     * @param string $status статус задачи на текущий момент
     * @return bool да\нет
     */
    protected function hasRights(int $implementorId, int $userId, int $customerId, string $status)
    {
        return $customerId === $userId && $status === 'in_progress';
    }
}
