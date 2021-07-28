<?php
namespace TaskForce\Task;

use Spatie\Enum\Enum;

/**
 * @method static self NEW()
 * @method static self CANCELLED()
 * @method static self IN_PROGRESS()
 * @method static self DONE()
 * @method static self FAILED()
 */
class TaskStatus extends \Spatie\Enum\Enum
{
    // Константы с описанием статусов задач
    const NEW = 'new_task';
    const CANCELLED = 'task_is_cancelled';
    const IN_PROGRESS = 'task_in_progress';
    const DONE = 'task_is_done';
    const FAILED = 'task_is_failed';


    /**
     * Возвращает «карты» статусов.
     * @return array Карта — это ассоциативный массив, где ключ — внутреннее имя, а значение — названия статуса на русском.
     */
    protected static function labels(): array
    {
        return [
            self::NEW => 'Новая задача',
            self::CANCELLED => 'Задача отменена',
            self::IN_PROGRESS => 'Задача в работе',
            self::DONE => 'Задача завершена',
            self::FAILED => 'Задача провалена',
        ];
    }
}
