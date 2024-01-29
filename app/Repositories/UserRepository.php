<?php

namespace App\Repositories;

use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\UserAlertLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseEloquentRepository
{
    protected $modelName = User::class;

    /**
     * @return mixed
     */
    public function getAlertLevels()
    {
        return UserAlertLevel::pluck('name', 'id')->toArray();
    }

    /**
     * @inheritDoc
     */
    public function getGrowthChartData(CarbonPeriod $period): array
    {
        $startingValue = DB::table('users')
            ->where(function (QueryBuilder $q) use ($period) {
                $q->where('deleted_at', '>=', $period->getStartDate())
                    ->orWhereNull('deleted_at');
            })
            ->where('created_at', '<', $period->getStartDate())
            ->count();

        $runningTotal = DB::table('users')
            ->selectRaw("date_format(created_at, '%d-%m-%Y') AS date, count(*) as total")
            ->where('created_at', '>=', $period->getStartDate())
            ->where('created_at', '<=', $period->getEndDate())
            ->groupBy('date')
            ->get();

        $unsubscribers = DB::table('users')
            ->selectRaw("date_format(deleted_at, '%d-%m-%Y') AS date, count(*) as total")
            ->where('deleted_at', '>=', $period->getStartDate())
            ->where('deleted_at', '<=', $period->getEndDate())
            ->groupBy('date')
            ->get();

        return [
            'startingValue' => $startingValue,
            'runningTotal' => $runningTotal->flatten()->keyBy('date'),
            'unsubscribers' => $unsubscribers->flatten()->keyBy('date'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applyClosedFilter($instance, $filters);
        $this->applyIdFilter($instance, $filters);
        // $this->applyUserIdFilter($instance, $filters);
        // $this->applyEmailFilter($instance, $filters);
        // $this->applyTelephoneFilter($instance, $filters);
        // $this->applyKYCFilter($instance, $filters);
        // $this->applyUserKindFilter($instance, $filters);
        $this->applyNameFilter($instance, $filters);
        $this->applyHuriganaFilter($instance, $filters);
        $this->applyUserCodeFilter($instance, $filters);
        
        $this->applyPaymentCodeFilter($instance, $filters);
        $this->applyAlertLevelFilter($instance, $filters);
        // $this->applyTimeFilter($instance, $filters);

        $this->applyRiskLevelFilter($instance, $filters);
    }

    /**
     * Filter by risk level.
     */
    protected function applyRiskLevelFilter(Builder $instance, array $filters = []): void
    {
        if ($riskLevels = Arr::get($filters, 'alert_level')) {
            $instance->whereIn('new_users.alert_level', $riskLevels);
        }
    }

    /**
     * Filter by closed status.
     */
    protected function applyClosedFilter(Builder $instance, array $filters = []): void
    {
        if (Arr::get($filters, 'closed')) {
            $instance->onlyTrashed();
        }
    }

    /**
     * Filter by id.
     */
    protected function applyIdFilter(Builder $instance, array $filters): void
    {
        if ($id = Arr::get($filters, 'id')) {
            $instance->where('id', $id);
        }

        if ($startId = Arr::get($filters, 'start_id')) {
            $instance->where('id', '>=', $startId);
        }

        if ($endId = Arr::get($filters, 'end_id')) {
            $instance->where('id', '<=', $endId);
        }
    }

    /**
     * Filter by user id.
     */
    protected function applyUserIdFilter(Builder $instance, array $filters): void
    {
        if ($userId = Arr::only($filters, ['c_id', 'uid', 'user_id'])) {
            $instance->whereIn('new_users.c_id', $userId);
        }
    }

    /**
     * Filter by email.
     */
    protected function applyEmailFilter(Builder $instance, array $filters): void
    {
        if ($email = Arr::get($filters, 'email')) {
            $filterString = '%'.$email.'%';
            $instance->where('new_users.email', 'like', $filterString);
        }
    }

    /**
     * Filter by telephone number.
     */
    protected function applyTelephoneFilter(Builder $instance, array $filters): void
    {
        if ($telephone = Arr::only($filters, ['tel', 'phone_number'])) {
            $filterString = '%'.$telephone.'%';
            $instance->where(function ($query) use ($filterString) {
                $query->where('new_users.tel', 'like', $filterString)
                    ->orWhere('new_users.phone_number', 'like', $filterString);
            });
        }
    }

    /**
     * Filter by kyc status.
     */
    protected function applyKYCFilter(Builder $instance, array $filters): void
    {
        if ($kyc = Arr::get($filters, 'kyc')) {
            $instance->whereIn('new_users.kyc', $kyc);
        }
    }

    /**
     * Filter by user kind status.
     */
    protected function applyUserKindFilter(Builder $instance, array $filters): void
    {
        if ($userKind = Arr::get($filters, 'user_kind')) {
            $instance->whereIn('new_users.user_kind', $userKind);
        }
    }

    /**
     * Filter by name.
     */
    protected function applyNameFilter(Builder $instance, array $filters): void
    {
        if ($name = Arr::get($filters, 'name')) {
            $names = explode(' ', $name);

            $instance->where(static function (Builder $instance) use ($names) {
                foreach ($names as $name) {
                    $filterString = '%'.$name.'%';
                    $instance->where('first_name', 'like', $filterString)
                        ->orWhere('last_name', 'like', $filterString);
                }
            });
        }
    }

    /**
     * Filter by hurigana.
     */
    protected function applyHuriganaFilter(Builder $instance, array $filters): void
    {
        if ($hurigana = Arr::get($filters, 'hurigana')) {
            $huriganas = explode(' ', $hurigana);

            $instance->where(static function (Builder $instance) use ($huriganas) {
                foreach ($huriganas as $hurigana) {
                    $filterString = '%'.$hurigana.'%';
                    $instance->where('hurigana', 'like', $filterString);
                }
            });
        }
    }

    /**
     * Filter by user code.
     */
    protected function applyUserCodeFilter(Builder $instance, array $filters): void
    {
        if ($userCode = Arr::get($filters, 'user_code')) {
            $instance->where('user_code', $userCode);
        }
    }

    /**
     * Filter by payment code.
     */
    protected function applyPaymentCodeFilter(Builder $instance, array $filters): void
    {
        if ($paymentCode = Arr::get($filters, 'payment_code')) {
            $instance->where('payment_code', $paymentCode);
        }
    }

    /**
     * Filter by alert level.
     */
    protected function applyAlertLevelFilter(Builder $instance, array $filters): void
    {
        if ($alertLevel = Arr::get($filters, 'alertLevels')) {
            $instance->whereIn('alertLevels', $alertLevel);
        }
    }

    /**
     * Filter by date and time.
     */
    protected function applyTimeFilter(Builder $instance, array $filters): void
    {
        if ($lastOrderStartTime = Arr::get($filters, 'last_order_start_time')) {
            $instance->where('new_users.last_order_created_at', '>=', $lastOrderStartTime.' 00:00:00');
        }

        if ($lastOrderEndTime = Arr::get($filters, 'last_order_end_time')) {
            $instance->where('new_users.last_order_created_at', '<=', $lastOrderEndTime.' 23:59:59');
        }

        if ($startTime = Arr::get($filters, 'start_time')) {
            $instance->where('new_users.created_at', '>=', $startTime.' 00:00:00');
        }

        if ($endTime = Arr::get($filters, 'end_time')) {
            $instance->where('new_users.created_at', '<=', $endTime.' 23:59:59');
        }
    }
}
