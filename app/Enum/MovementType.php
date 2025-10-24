<?php

namespace App\Enum;

enum MovementType
{
    'purchase_in',
                'purchase_out',
                'transfer',
                'transfer_reversal',
                'unit_to_inventory',
                'sale',
                'adjustment',
                'reservation',
                'carton_creation',
                'stock_reconciliation'
}
