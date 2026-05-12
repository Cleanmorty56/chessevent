<?php
echo "<h2>Проверка модулей Apache</h2>";

if (function_exists('apache_get_modules')) {
    $modules = apache_get_modules();
    if (in_array('mod_proxy', $modules)) {
        echo "✅ mod_proxy - ВКЛЮЧЕН<br>";
    } else {
        echo "❌ mod_proxy - ОТКЛЮЧЕН<br>";
    }
    if (in_array('mod_proxy_wstunnel', $modules)) {
        echo "✅ mod_proxy_wstunnel - ВКЛЮЧЕН<br>";
    } else {
        echo "❌ mod_proxy_wstunnel - ОТКЛЮЧЕН<br>";
    }
} else {
    echo "Функция apache_get_modules() недоступна. Проверьте через техподдержку.<br>";
}
?>