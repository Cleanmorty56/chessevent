#!/usr/bin/env php
<?php
// daemon.php – управление Workerman-сервером
$pidFile = __DIR__ . '/workerman.pid';
$serverScript = __DIR__ . '/workerman-server.php';
$command = $argv[1] ?? 'status';

function isRunning($pidFile) {
    if (!file_exists($pidFile)) return false;
    $pid = file_get_contents($pidFile);
    if (!is_numeric($pid)) return false;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        exec("tasklist /FI \"PID eq $pid\" 2>NUL", $out);
        return count($out) > 1;
    } else {
        exec("ps -p $pid", $out);
        return count($out) > 1;
    }
}

function start($pidFile, $serverScript) {
    if (isRunning($pidFile)) {
        echo "Уже запущен (PID ".file_get_contents($pidFile).")\n";
        return;
    }
    $cmd = "nohup php $serverScript > /dev/null 2>&1 & echo $!";
    $pid = exec($cmd);
    file_put_contents($pidFile, $pid);
    echo "Запущен, PID: $pid\n";
}

function stop($pidFile) {
    if (!isRunning($pidFile)) {
        echo "Не запущен\n";
        return;
    }
    $pid = file_get_contents($pidFile);
    exec("kill -9 $pid");
    unlink($pidFile);
    echo "Остановлен\n";
}

function status($pidFile) {
    echo isRunning($pidFile) ? "Работает\n" : "Не работает\n";
}

switch ($command) {
    case 'start':  start($pidFile, $serverScript); break;
    case 'stop':   stop($pidFile); break;
    default:       status($pidFile);
}