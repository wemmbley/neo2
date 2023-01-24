<?php

namespace Neo\Core\App\Modules\Protector;

class Protector
{
    public static function str(string $str)
    {
        return htmlspecialchars($str);
    }

    public static function csrf()
    {
        if (!isset($_SESSION["csrf"])) {
            $_SESSION["csrf"] = bin2hex(random_bytes(50));
        }

        echo '<input type="hidden" name="csrf" value="' . $_SESSION["csrf"] . '">';
    }

    public static function isCsrf()
    {
        if (!isset($_SESSION['csrf']) || !isset($_POST['csrf'])) {
            return false;
        }

        if ($_SESSION['csrf'] != $_POST['csrf']) {
            return false;
        }

        return true;
    }
}