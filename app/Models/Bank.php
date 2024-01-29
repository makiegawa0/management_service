<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    public const MUFG = 1;
    public const SMBC = 2;
    public const RAKUTEN = 3;
    public const MIZUHO = 4;
    public const PAYPAY = 5;

    /** @var bool */
    public $timestamps = false;

    protected $table = 'banks';

    /** @var array */
    protected static $levels = [
        self::MUFG => 'MUFG',
        self::SMBC => 'SMBC',
        self::RAKUTEN => 'Rakuten',
        self::MIZUHO => 'Mizuho',
        self::PAYPAY => 'PayPay',
    ];
}
