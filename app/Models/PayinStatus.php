<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayinStatus extends Model
{
    public const UNPROCESSED = 1;
    public const AUTO = 2;
    public const MANUAL = 3;
    public const ALERT = 4;
    public const BLOCKED = 5;
    public const CLOSED = 6;

    /** @var bool */
    public $timestamps = false;

    protected $table = 'payin_statuses';

    /** @var array */
    protected static $levels = [
        self::UNPROCESSED => 'Unprocessed',
        self::AUTO => 'Auto',
        self::MANUAL => 'Manual',
        self::ALERT => 'Alert',
        self::BLOCKED => 'Blocked',
        self::CLOSED => 'Closed',
    ];
}
