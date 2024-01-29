<?php

namespace App\Models;

use App\Library\Common;
use App\Services\Helper;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends BaseModel
{
    use Notifiable, SoftDeletes, HasFactory;

    /** @var string */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_code',
        'email',
        'telephone',
        'first_name',
        'last_name',
        'payment_code',
        'hurigana',
        'kyc',
        'user_kind',
        'alert_level_id',
    ];

    protected $dates = ['last_order_created_at'];

    /**
     * Alert level of the user.
     */
    public function alert_level(): BelongsTo
    {
        return $this->belongsTo(UserAlertLevel::class);
    }

    /**
     * Generate payment code.
     *
     * @return string
     */
    public static function generatePaymentCode(array $aAddCheckProcessCode = [])
    {
        return Helper::generateUniqueRandomStr(function ($sCode) use ($aAddCheckProcessCode) {
            if ($aAddCheckProcessCode && in_array($sCode, $aAddCheckProcessCode)) {
                return '';
            }
            
            if (self::where('payment_code', $sCode)->exists()) {
                return '';
            }

            return $sCode;
        }, config('const.PAYMENT_CODE_LENGTH'), config('messages.error.user.failed_to_generate_payment_code'), 901);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    /**
     * Whether the user is normal level.
     */
    public function getNormalAttribute(): bool
    {
        return $this->alert_level_id === UserAlertLevel::NORMAL;
    }

    /**
     * Whether the user is alert level.
     */
    public function getAlertAttribute(): bool
    {
        return $this->alert_level_id === UserAlertLevel::ALERT;
    }

    /**
     * Whether the user is blocked.
     */
    public function getBlockedAttribute(): bool
    {
        return $this->alert_level_id === UserAlertLevel::BLOCK;
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(
            function ($model) {
                $model->payment_code = self::generatePaymentCode();
            }
        );
        // static::deleting(
            // function (self $user) {
                // $user->tags()->detach();
                // $user->messages()->each(static function (Message $message) {
                //     $message->failures()->delete();
                // });
                // $user->messages()->delete();
            // }
        // );
    }


    public function payment_requests(): HasMany
    {
        return $this->hasMany(NewOrder::class)->orderBy('id', 'desc');
    }

    /**
     * ユーザ振り込みリレーション
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function user_transfer_accounts()
    {
        return $this->hasMany('App\Models\UserTransferAccount', 'user_id');
    }

    /**
     * Get the phone associated with the user.
     */
    public function alert_user_info(): HasOne
    {
        return $this->hasOne(AlertUserInfo::class, 'user_id');
    }

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
            $oQuery->where('new_users.id', $oRequests->id);
        } else {
            if ($oRequests->filled('start_id')) {
                $oQuery->where('new_users.id', '>=', $oRequests->start_id);
            }
            if ($oRequests->filled('end_id')) {
                $oQuery->where('new_users.id', '<=', $oRequests->end_id);
            }
        }

        if ($oRequests->filled('c_id')) {
            $oQuery->where('new_users.c_id', $oRequests->c_id);
        }

        if ($oRequests->filled('uid')) {
            $oQuery->where('new_users.c_id', $oRequests->uid);
        }

        if ($oRequests->filled('email')) {
            $oQuery->where('new_users.email', $oRequests->email);
        }

        if ($oRequests->filled('tel')) {
            $oQuery->where('new_users.tel', $oRequests->tel);
        }

        if ($oRequests->filled('kyc')) {
            if (is_array($oRequests->kyc)) {
                $oQuery->whereIn('new_users.kyc', $oRequests->kyc);
            } else {
                $oQuery->where('new_users.kyc', '=', $oRequests->kyc);
            }
        }

        if ($oRequests->filled('user_kind')) {
            if (is_array($oRequests->user_kind)) {
                $oQuery->whereIn('new_users.user_kind', $oRequests->user_kind);
            } else {
                $oQuery->where('new_users.user_kind', '=', $oRequests->user_kind);
            }
        }

        if ($oRequests->filled('keyword')) {
            $aKeyword = explode(' ', $oRequests->keyword);
            $oQuery->Where(function ($oQuerykeywordArray) use ($aKeyword) {
                foreach ($aKeyword as $sKeyword) {
                    if ($sKeyword) {
                        $oQuerykeywordArray->orWhere(function ($oQuerykeyword) use ($sKeyword) {
                            $oQuerykeyword->where('new_users.fname', 'LIKE', '%'.$sKeyword.'%');
                            $oQuerykeyword->orWhere('new_users.lname', 'LIKE', '%'.$sKeyword.'%');
                            $oQuerykeyword->orWhere('new_users.keyword', 'LIKE', '%'.$sKeyword.'%');
                        });
                    }
                }
            });
        }

        if ($oRequests->filled('process_code')) {
            $oQuery->where('new_users.process_code', $oRequests->process_code);
        }

        if ($oRequests->filled('alert_level')) {
            if (is_array($oRequests->alert_level)) {
                $oQuery->whereIn('new_users.alert_level', $oRequests->alert_level);
            } else {
                $oQuery->where('new_users.alert_level', '=', $oRequests->alert_level);
            }
        }

        if ($oRequests->filled('last_order_start_time')) {
            $oQuery->where('new_users.last_order_created_at', '>=', $oRequests->last_order_start_time.' 00:00:00');
        }
        if ($oRequests->filled('last_order_end_time')) {
            $oQuery->where('new_users.last_order_created_at', '<=', $oRequests->last_order_end_time.' 23:59:59');
        }

        if ($oRequests->filled('start_time')) {
            $oQuery->where('new_users.created_at', '>=', $oRequests->start_time.' 00:00:00');
        }
        if ($oRequests->filled('end_time')) {
            $oQuery->where('new_users.created_at', '<=', $oRequests->end_time.' 23:59:59');
        }

        //$oQuery->groupBy('new_users.id');
        $oQuery->orderBy('new_users.id', 'DESC');

        return $oQuery;
    }

    /**
     * whereHas用のクエリ
     *
     * @return void
     */
    public static function whereHas($oQuery, Request $oRequests)
    {
        if ($oRequests->filled('c_id')) {
            $oQuery->where('new_users.c_id', $oRequests->c_id);
        }

        if ($oRequests->filled('tel')) {
            $oQuery->where('new_users.tel', $oRequests->tel);
        }

        if ($oRequests->filled('email')) {
            $oQuery->where('new_users.email', $oRequests->email);
        }

        if ($oRequests->filled('keyword')) {
            $aKeyword = explode(' ', $oRequests->keyword);
            $oQuery->Where(function ($oQuerykeywordArray) use ($aKeyword) {
                foreach ($aKeyword as $sKeyword) {
                    if ($sKeyword) {
                        $oQuerykeywordArray->orWhere(function ($oQuerykeyword) use ($sKeyword) {
                            $oQuerykeyword->where('new_users.fname', 'LIKE', '%'.$sKeyword.'%');
                            $oQuerykeyword->orWhere('new_users.lname', 'LIKE', '%'.$sKeyword.'%');
                            $oQuerykeyword->orWhere('new_users.keyword', 'LIKE', '%'.$sKeyword.'%');
                        });
                    }
                }
            });
        }
    }

    /**
     * 旧ユーザ情報から作成
     *
     * @param  App\Models\User  $oUser
     *
     * @return int id
     */
    public function createByOldUser(User $oUser)
    {
        return self::insertGetId([
            'id' => $oUser->id,
            'c_id' => $oUser->before_user_id,
            'fname' => $oUser->first_name,
            'lname' => $oUser->last_name,
            'tel' => $oUser->tel,
            'email' => $oUser->email,
            'kyc' => $oUser->kyc,
            'user_kind' => $oUser->user_kind,
            'process_code' => self::getGenerateProcessCode(),
            'alert_level' => config('const.ALERT_USER_INFO_LEVEL_NORMAL'),
            'created_at' => $oUser->created_at,
            'updated_at' => now(),
        ]);
    }

    /**
     * API Logリレーション
     *
     * @return Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function incomingLogs()
    {
        return $this->hasMany(IncomingLog::class, 'order_cid', 'c_id');
    }

}
