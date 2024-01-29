<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseModel
{
    use HasFactory;

    /** @var string */
    // protected $table = 'settings';

    /** @var array<string> */
    protected $fillable = [
        'reverse_cb',
        'user_import',
        'user_individual_deletion',
        'user_deletion',
        'payment_check',
        'payment_request_check',
        'balance_check',
    ];
}
