<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAlertLevel extends BaseModel
{
    protected $table = 'user_alert_levels';

    const NORMAL = 1;
    const ALERT = 2;
    const BLOCK = 3;

    public static $levels = [
        1 => 'Normal',
        2 => 'Alert',
        3 => 'Block',
    ];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the alert level by ID
     *
     * @param int $id
     * @return mixed
     */
    public static function findById($id): string
    {
        return \Arr::get(static::$levels, $id);
    }

    /**
     * Resolve a level ID to a level name.
     */
    public static function resolve(int $levelId): ?string
    {
        return static::$levels[$levelId] ?? null;
    }
}
