<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class AccessLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'ip',
        'referrer',
    ];

    /**
     * 検索条件
     *
     * @param  Illuminate\Database\Query\Builder  $oQuery
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeSearch($oQuery, Request $oRequests)
    {
        if ($oRequests->filled('id')) {
            $oQuery->where('access_logs.id', $oRequests->id);
        }

        if ($oRequests->filled('start_time')) {
            $oQuery->where('access_logs.created_at', '>=', $oRequests->start_time.' 00:00:00');
        }
        if ($oRequests->filled('end_time')) {
            $oQuery->where('access_logs.created_at', '<=', $oRequests->end_time.' 23:59:59');
        }

        $oQuery->orderBy('access_logs.id', 'DESC');

        return $oQuery;
    }
}
