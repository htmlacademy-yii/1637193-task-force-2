<?php
namespace TaskForce\Task;

use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static self new()
 * @method static self cancelled()
 * @method static self in_progress()
 * @method static self done()
 * @method static self failed()
 */
class TaskStatusEnum extends \Spatie\Enum\Enum
{
    /**
     * Возвращает «карты» статусов.
     * @return array Карта — это ассоциативный массив, где ключ — внутреннее имя, а значение — названия статуса на русском.
     */
    #[ArrayShape([
        'new' => "string",
        'cancelled' => "string",
        'in_progress' => "string",
        'done' => "string",
        'failed' => "string"
    ])] protected static function labels(): array
    {
        return [
            'new' => 'Новая задача',
            'cancelled' => 'Задача отменена',
            'in_progress' => 'Задача в работе',
            'done' => 'Задача завершена',
            'failed' => 'Задача провалена',
        ];
    }
}
