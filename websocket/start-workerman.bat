@echo off
chcp 65001
title WORKERMAN WEBSOCKET
echo ========================================
echo    ЗАПУСК WORKERMAN СЕРВЕРА
echo ========================================
cd /d "C:\xampp\htdocs\dip1om_lakaev"
php websocket/workerman-server.php
pause