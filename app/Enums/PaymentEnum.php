<?php
namespace App\Enums;
class PaymentEnum
{
    public static $STATUS = [
        0 => 'Создан',
        1 => 'Оплачен',
        2 => 'Отклоненный',
        3 => 'Истекший',
        4 => 'Другая ошибка оплаты',
    ];
}
