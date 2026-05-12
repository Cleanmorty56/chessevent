<?php
exec('cd /var/www/gr1320229/data/www/chess-event.kpk45.ru && php websocket/daemon.php stop');
sleep(2);
exec('cd /var/www/gr1320229/data/www/chess-event.kpk45.ru && php websocket/daemon.php start');
echo "Перезапущено";
?>