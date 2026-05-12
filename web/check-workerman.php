<?php
$connection = @fsockopen('127.0.0.1', 8085, $errno, $errstr, 2);
if ($connection) {
    echo "✅ Workerman ДОСТУПЕН на порту 8085<br>";
    fclose($connection);
} else {
    echo "❌ Workerman НЕ ДОСТУПЕН на порту 8085<br>";
    echo "Ошибка: $errstr<br>";
}

// Дополнительная проверка
$ch = curl_init('http://127.0.0.1:8085');
curl_setopt($ch, CURLOPT_TIMEOUT, 2);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);
echo "HTTP код: " . ($info['http_code'] ?? 'нет ответа');
?>