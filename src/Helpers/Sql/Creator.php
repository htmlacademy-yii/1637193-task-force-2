<?php

namespace TaskForce\Helpers\Sql;


class Creator
{
    public array $data;

    public function __construct(private array $row, private string $table)
    {
    }

    /**
     * Возвращает строку из элементов массива
     * @return string Строка из элементов массива
     */
    private function getRow(): string
    {
        return trim(implode(', ', $this->row));
    }

    /**
     * Подготавливает данные строки из csv-файла в sql-запрос для добавления в таблицу
     * @param array|null $data Данные из csv-файла
     * @return string
     */
    public function getQuery($data): string
    {
        $value = $this->getValues($data);
        return 'INSERT INTO ' . $this->getTableName() . '(' . $this->getRow() . ') VALUES (' . $value . ');';

    }

    /**
     * Получает значения в виде string строки из одной строки csv-файла
     * @param array|null $data Данные из csv-файла
     * @return string|null Разделенные значения строки либо Null при отсутствии данных
     */
    public function getValues($data): ?string
    {
        if (!empty($data)) {
            return implode(
                ', ',
                array_map(function ($string) {
                    return '"' . $string . '"';
                }, $data)
            );
        }

        return null;
    }

    /**
     * Получает имя таблицы
     * @return string Строка с именем таблицы
     */
    public function getTableName(): string
    {
        $tableName = substr($this->table, 0, strpos($this->table, '.'));
        return substr($tableName, 5);
    }
}
