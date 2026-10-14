<?php

/**
 * Классы Belveb\Booking\* подключает Loader::includeModule('belveb.booking').
 * Для модуля с точкой в имени ядро само регистрирует PSR-4: Belveb\Booking → lib/.
 *
 * Engine ищет контроллеры по нижнему регистру (lib/controller/room.php).
 * Если на диске осталась только копия lib/Controller/Room.php, подключаем её здесь:
 * иначе действие уходит в DefaultController.
 */
foreach (['room', 'booking', 'user'] as $belvebBookingController) {
    $lower = __DIR__ . '/lib/controller/' . $belvebBookingController . '.php';
    $pascal = __DIR__ . '/lib/Controller/' . ucfirst($belvebBookingController) . '.php';
    if (is_file($lower) && filesize($lower) > 64) {
        require_once $lower;
        continue;
    }
    if (is_file($pascal) && filesize($pascal) > 64) {
        require_once $pascal;
    }
}
unset($belvebBookingController, $lower, $pascal);
