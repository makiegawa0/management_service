<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLogStatus extends BaseModel
{
    public const PENDING = 1;
    public const CONFIRMED = 2;

    /** @var bool */
    public $timestamps = false;

    protected $table = 'api_log_statuses';

    /** @var array */
    protected static $statuses = [
        self::PENDING => 'Pending',
        self::CONFIRMED => 'Confirmed'
    ];
}
