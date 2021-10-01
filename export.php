<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once 'vendor/autoload.php';

use Taskforce\Exceptions\FileException;
use TaskForce\Helpers\File\CsvExporter;

$files = [
    'data/users.csv',
    'data/categories.csv',
    'data/cities.csv',
    'data/profiles.csv',
    'data/replies.csv',
    'data/tasks.csv'
];

foreach ($files as $file) {
    $ExportCsv = new CsvExporter($file);

    $ExportCsv->export();
    $ExportCsv->createSQLFile();

    try {
        $ExportCsv->export();
        $ExportCsv->createSQLFile();
    } catch (FileException $e) {
        echo "Ошибка создания файла", $e->getMessage();
    }
}
