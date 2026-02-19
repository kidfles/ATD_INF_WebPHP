<?php declare(strict_types=1);

namespace App\Enums;

enum AdvertisementType: string
{
    case Sale    = 'sale';
    case Rent    = 'rent';
    case Auction = 'auction';
}
