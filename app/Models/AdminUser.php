<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\Factory;
use Database\Factories\AdminUserFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminUser extends Authenticatable
{
    use SoftDeletes, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level_id'
    ];

    /**
     * Store which fields are boolean in the model
     *
     * Note that any boolean fields not in the fillable
     * array will not be automatically set in the repo
     *
     * @var array
     */
    protected $booleanFields = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Level of the admin user.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(AdminUserLevel::class);
    }

    /**
     * Return all boolean fields for the model
     *
     * @return array
     */
    public function getBooleanFields()
    {
        return $this->booleanFields;
    }

    /**
     * Whether the user is admin level.
     */
    public function getAdminAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::ADMIN;
    }

    /**
     * Whether the user is viewer.
     */
    public function getViewerAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::VIEWER;
    }

    /**
     * Whether the user is operator.
     */
    public function getOperatorAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::OPERATOR;
    }

    /**
     * Whether the user is setter.
     */
    public function getSetterAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::CALLBACK_SETTER;
    }

    /**
     * Whether the user is manager.
     */
    public function getManagerAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::CALLBACK_MANAGER;
    }

    /**
     * Whether the user is uploader.
     */
    public function getUploaderAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::UPLOADER;
    }

    /**
     * Whether the user is accountant.
     */
    public function getAccountantAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::ACCOUNTANT;
    }

    /**
     * Whether the user is restricted.
     */
    public function getRestrictAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::RESTRICT;
    }

    /**
     * Whether the user is root.
     */
    public function getRootAttribute(): bool
    {
        return $this->level_id === AdminUserLevel::ROOT;
    }

    // /**
    //  * 検索条件
    //  *
    //  * @param  Illuminate\Database\Query\Builder  $oQuery
    //  *
    //  * @return Illuminate\Database\Query\Builder
    //  */
    // public function scopeSearch($oQuery, Request $oRequests)
    // {
    //     if ($oRequests->filled('id')) {
    //         $oQuery->where('admin_users.id', $oRequests->id);
    //     }

    //     if ($oRequests->filled('name')) {
    //         $oQuery->where('admin_users.name', $oRequests->name);
    //     }

    //     if ($oRequests->filled('email')) {
    //         $oQuery->where('admin_users.email', $oRequests->email);
    //     }

    //     if ($oRequests->filled('level_id')) {
    //         $oQuery->where('admin_users.level_id', $oRequests->level_id);
    //     }

    //     if ($oRequests->filled('permission')) {
    //         $oQuery->where('admin_users.level_id', $oRequests->permission);
    //     }

    //     if ($oRequests->filled('status')) {
    //         $oQuery->where('admin_users.status', $oRequests->status);
    //     }

    //     // 検索結果などにはroot権限を表示させない
    //     $oQuery->where('admin_users.level_id', '!=', config('const.ADMIN_USER_LEVEL_ROOT'));

    //     // $oQuery->groupBy('admin_users.id');
    //     $oQuery->orderBy('admin_users.id', 'DESC');

    //     return $oQuery;
    // }

    /**
     * Checks if user is Admin level.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->level_id === AdminUserLevel::ADMIN || $this->level_id === AdminUserLevel::ROOT;
    }

    /**
     * Checks if user is Viewer level.
     *
     * @return bool
     */
    public function isViewer()
    {
        return $this->level_id === AdminUserLevel::VIEWER;
    }

    /**
     * Checks if user is Operator level.
     *
     * @return bool
     */
    public function isOperator()
    {
        return $this->level_id === AdminUserLevel::OPERATOR;
    }

    /**
     * Checks if user is Restrict level.
     *
     * @return bool
     */
    public function isRestrict()
    {
        return $this->level_id === AdminUserLevel::RESTRICT;
    }

    /**
     * Checks if user is Callback setter level.
     *
     * @return bool
     */
    public function isCallbackSetter()
    {
        return $this->level_id == AdminUserLevel::CALLBACK_SETTER;
    }

    /**
     * Checks if user is Callback manager level.
     *
     * @return bool
     */
    public function isCallbackManager()
    {
        return $this->level_id === AdminUserLevel::CALLBACK_MANAGER;
    }

    /**
     * Checks if user is Uploader level.
     *
     * @return bool
     */
    public function isUploader()
    {
        return $this->level_id == AdminUserLevel::UPLOADER;
    }

    /**
     * Checks if user is Accountant level.
     *
     * @return bool
     */
    public function isAccountant()
    {
        return $this->level_id === AdminUserLevel::ACCOUNTANT;
    }
}
