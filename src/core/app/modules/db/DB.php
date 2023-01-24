<?php

declare(strict_types=1);

namespace Neo\Core\App\Modules\DB;

use Exception;
use PDO;

class DB
{
    protected static PDO $pdo;
    protected static $lastQuery;

    protected function __construct() {}
    protected function __clone() {}

    public static function setup()
    {
        $dsn = env('DB_CONNECTION') . ':host=' . env('DB_HOST') . ';dbname=' . env('DB_DATABASE');

        static::$pdo = new PDO($dsn, env('DB_USERNAME'), env('DB_PASSWORD'));
    }

    public static function query(string $sql, array $params = [])
    {
        static::$lastQuery = static::$pdo->prepare($sql);
        static::$lastQuery->execute($params);

        return new static();
    }

    public static function toArray()
    {
        return static::$lastQuery->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function toObject()
    {
        return static::$lastQuery->fetchAll(PDO::FETCH_OBJ);
    }
}