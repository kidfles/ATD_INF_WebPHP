<?php declare(strict_types=1);

namespace App\Enums;

enum AdvertisementType: string
{
    case Sale    = 'sell';
    case Rent    = 'rent';
    case Auction = 'auction';
}
