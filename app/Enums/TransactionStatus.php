<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Paid = 'Paid';
    case Outstanding = 'Outstanding';
    case Overdue = 'Overdue';
}
