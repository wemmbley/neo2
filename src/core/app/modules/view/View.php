<?php

declare(strict_types=1);

namespace Neo\Core\App\Modules\View;

class View
{
    public function viewTheme(string $template, array $args = [])
    {
        $templatePath = abspath('/themes/' . env('THEME_ACTIVE') . '/html/' . $template);

        if (!file_exists($templatePath)) {
            throw new \Exception('File ' . $templatePath . ' not found');
        }

        ob_start();
        extract($args);
        require_once $templatePath;

        return ob_end_flush();
    }

    public function viewPlugin(string $plugin, string $template, array $args = [])
    {
        $pluginPath = abspath('/plugins/' . $plugin . '/html/' . $template);
        $templatePath = abspath('/themes/' . env('THEME_ACTIVE') . '/html/' . $plugin . '/' . $template);

        ob_start();
        extract($args);

        if (file_exists($templatePath)) {
            require_once $templatePath;

            return ob_end_flush();
        }

        if (file_exists($pluginPath)) {
            require_once $pluginPath;

            return ob_end_flush();
        }

        throw new \Exception('Template ' . $template . ' not found');
    }
}