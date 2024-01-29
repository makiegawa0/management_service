<?php

declare(strict_types=1);

namespace App\Models;

class PaymentRequestStatus extends BaseModel
{
    public const UNPROCESSED = 1;
    public const AUTO = 2;
    public const MANUAL = 3;
    public const FAILED = 4;

    /** @var bool */
    public $timestamps = false;

    protected $table = 'payment_request_statuses';

    /** @var array */
    protected static $statuses = [
        self::UNPROCESSED => 'Unprocessed',
        self::AUTO => 'Auto',
        self::MANUAL => 'Manual',
        self::FAILED => 'Failed',
    ];

    /**
     * Resolve a level ID to a level name.
     */
    public static function resolve(int $statusId): ?string
    {
        return static::$statuses[$statusId] ?? null;
    }
}
