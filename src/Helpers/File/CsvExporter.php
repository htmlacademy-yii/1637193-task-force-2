<?php

namespace TaskForce\Helpers\File;

use SplFileObject;
use TaskForce\Exceptions\FileException;
use TaskForce\Helpers\Sql\Creator;

class CsvExporter
{
    private SplFileObject $fileObject;
    private Creator $sqlCreator;

    public function __construct(private string $fileName)
    {
    }

    /**
     * Создаёт новый файловый объект SplFileObject.
     * @throws FileException Исключение на случай отсутствия файла с названием равным переданной строке
     */
    public function export(): void
    {
        if (!file_exists($this->fileName)) {
            throw new FileException('Запрашиваемый файл ' . '`' . $this->fileName . '`' . ' не существует');
        }
        $this->fileObject = new SplFileObject($this->fileName);
        $this->fileObject->setFlags(SplFileObject::SKIP_EMPTY | SplFileObject::DROP_NEW_LINE);
    }

    /**
     * Возвращает заголовки всех столбцов csv-файла в виде массива
     * @return array|null Массив, состоящий из элементов в виде текста внутри столбцов 1й строки csv-файла
     * либо Null при неудаче считывания файла \ пустых значениях
     */
    public function getTitle(): ?array
    {
        $this->fileObject->rewind();
        return $this->fileObject->fgetcsv();
    }


    /**
     * Считывает строку файла
     * @return iterable|null Массив, состоящий из элементов в виде текста внутри столбцов строки csv-файла
     * либо Null при неудаче считывания файла \ пустых значениях
     */
    public function getNextRow(): ?iterable
    {
        while (!$this->fileObject->eof()) {
            yield $this->fileObject->fgetcsv();
        }
    }

    /**
     * Создает SQL-файл из csv-файла
     * @throws FileException Исключение на случай невозможности получить данные из csv-файла
     */
    public function createSQLFile(): void
    {
        $this->sqlCreator = new Creator($this->getTitle(), $this->fileName);

        $fp = fopen('data/' . $this->sqlCreator->getTableName() . '.sql', "w");

        if (!$fp) {
            throw new FileException("Не удалось получить данные из файла " . $this->fileName);
        }

        foreach ($this->getNextRow() as $line) {
            $content = $this->sqlCreator->getQuery($line);
            fwrite($fp, $content);
        }

        fclose($fp);
    }
}
