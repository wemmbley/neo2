<?php

declare(strict_types=1);

namespace Neo\Core\App\Modules\Yaml;

class YAML
{
    protected static string $fileName;

    protected function __construct() {}
    protected function __clone() {}

    public static function file(string $fileName): static
    {
        static::$fileName = $fileName;

        return new static();
    }

    public function toArray()
    {
        return yaml_parse_file(static::$fileName);
    }
}