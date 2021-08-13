<?php
namespace TaskForce\Task;

use JetBrains\PhpStorm\ArrayShape;

/**
 * @method static self add_new()
 * @method static self cancel()
 * @method static self respond()
 * @method static self complete()
 * @method static self refuse()
 */
class TaskActionEnum extends \Spatie\Enum\Enum
{
    /**
     * Возвращает «карты» действий.
     * @return array Карта — это ассоциативный массив, где ключ — внутреннее имя, а значение — названия действия на русском.
     */
    #[ArrayShape([
        'add_new' => "string",
        'cancel' => "string",
        'respond' => "string",
        'complete' => "string",
        'refuse' => "string"
    ])] protected static function labels(): array
    {
        return [
            'add_new' => 'Добавить новую задачу',
            'cancel' => 'Отменить задачу',
            'respond' => 'Откликнуться на задачу',
            'complete' => 'Завершить задачу',
            'refuse' => 'Отказаться от задачи',
        ];
    }
}
