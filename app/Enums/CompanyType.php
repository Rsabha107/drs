<?php

namespace App\Enums;

enum CompanyType: string
{
    case Vendor = 'contractor';
    case Customer = 'agency';
    case Partner = 'caterer';

    public static function options(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Vendor => 'Contractor',
            self::Customer => 'Agency',
            self::Partner => 'Caterer',
        };
    }
}

