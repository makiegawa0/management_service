<?php

declare(strict_types=1);

namespace App\Models;

class AdminUserLevel extends BaseModel
{
    public const ADMIN = 1;
    public const VIEWER = 2;
    public const OPERATOR = 3;
    public const CALLBACK_SETTER = 4;
    public const CALLBACK_MANAGER = 5;
    public const UPLOADER = 6;
    public const ACCOUNTANT = 7;
    public const RESTRICT = 8;
    public const ROOT = 9;

    /** @var bool */
    public $timestamps = false;

    protected $table = 'admin_user_levels';

    /** @var array */
    protected static $levels = [
        self::ADMIN => 'Admin',
        self::VIEWER => 'Viewer',
        self::OPERATOR => 'Operator',
        self::CALLBACK_SETTER => 'Setter',
        self::CALLBACK_MANAGER => 'Manager',
        self::UPLOADER => 'Uploader',
        self::ACCOUNTANT => 'Accountant',
        self::RESTRICT => 'Restrict',
        self::ROOT => 'Root',
    ];

    /**
     * Resolve a level ID to a level name.
     */
    public static function resolve(int $levelId): ?string
    {
        return static::$levels[$levelId] ?? null;
    }

    /**
     * Get all level IDs mapped to corresponding level names.
     */
    public static function getLevelsToNames(): ?array
    {
        return static::$levels;
    }

    /**
     * Get all level IDs.
     */
    public static function getLevels(): ?array
    {
        return array_keys(static::$levels);
    }
}
