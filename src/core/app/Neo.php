<?php

declare(strict_types=1);

namespace Neo\Core\App;

class Neo extends \ArrayObject
{
    private string $selectedModule = '';

    public function module(string $name)
    {
        $neo = $this->getArrayCopy();

        if (!isset($neo['modules'][$name])) {
            throw new \Exception('Module ' . $name . ' not found in Neo.');
        }

        $this->selectedModule = $name;

        return $this;
    }

    public function get(string $name = '')
    {
        $neo = $this->getArrayCopy();

        if (empty($this->selectedModule)) {
            throw new \Exception('Module not selected. Select it before get()');
        }

        if (!empty($name)) {
            if (!isset($neo['modules'][$this->selectedModule][$name])) {
                throw new \Exception('Class '. $name .' in module '. $this->selectedModule .' not found in Neo.');
            }

            return $neo['modules'][$this->selectedModule][$name];
        }

        return $neo['modules'][$this->selectedModule];
    }
}