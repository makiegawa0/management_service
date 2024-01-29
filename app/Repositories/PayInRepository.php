<?php

namespace App\Repositories;

use App\Models\Payin;
use App\Models\PayinStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class PayInRepository extends BaseEloquentRepository
{
    protected $modelName = Payin::class;

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return PayinStatus::pluck('name', 'id')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applyIdFilter($instance, $filters);
        $this->applyUserCodeFilter($instance, $filters);
        $this->applyInputFilter($instance, $filters);
        $this->applyPaymentCodeFilter($instance, $filters);

        $this->applyTimeFilter($instance, $filters);
        $this->applyStatusFilter($instance, $filters);

        $this->applyAmountFilter($instance, $filters);
    }

    /**
     * Filter by id.
     */
    protected function applyIdFilter(Builder $instance, array $filters): void
    {
        if ($id = Arr::get($filters, 'id')) {
            $instance->where('id', $id);
        }
    }

    /**
     * Filter by status.
     */
    protected function applyStatusFilter(Builder $instance, array $filters = []): void
    {
        // $statuses = Arr::get($filters, 'statuses');
        // dd($statuses);
        if ($statuses = Arr::get($filters, 'statuses')) {
            $instance->whereIn('status_id', array_map('intval', $statuses));
        }
    }

    /**
     * Filter by payment code.
     */
    protected function applyPaymentCodeFilter(Builder $instance, array $filters): void
    {
        if ($paymentCode = Arr::get($filters, 'payment_code')) {
            // $instance->where('users.payment_code', $paymentCode);
            // $instance->select('payins.*')
            //     ->leftJoin('users', 'payins.payment_request_id', '=', 'users.id')
            //     ->where('users.payment_code', $paymentCode);
            $instance->select('payins.*')
                ->leftJoin('payment_requests', 'payins.payment_request_id', '=', 'payment_requests.id')
                ->leftJoin('users', 'payment_requests.user_id', '=', 'users.id')
                ->where('users.payment_code', $paymentCode);                
        }
    }

    /**
     * Filter by input.
     */
    protected function applyInputFilter(Builder $instance, array $filters): void
    {
        if ($input = Arr::get($filters, 'input')) {
            $instance->where('input', 'like', '%'.$input.'%');
        }
    }

    /**
     * Filter by user code.
     */
    protected function applyUserCodeFilter(Builder $instance, array $filters): void
    {
        if ($userCode = Arr::get($filters, 'user_code')) {
            // $instance->where('user_code', 'like', '%'.$userCode.'%');
            // $instance->where('users.user_code', $userCode);
            $instance->select('payins.*')
                ->leftJoin('payment_requests', 'payins.payment_request_id', '=', 'payment_requests.id')
                ->leftJoin('users', 'payment_requests.user_id', '=', 'users.id')
                ->where('users.user_code', $userCode);
        }
    }

    /**
     * Filter by date and time.
     */
    protected function applyTimeFilter(Builder $instance, array $filters): void
    {
        if ($startTime = Arr::get($filters, 'start_time')) {
            $instance->where('created_at', '>=', $startTime.' 00:00:00');
        }

        if ($endTime = Arr::get($filters, 'end_time')) {
            $instance->where('created_at', '<=', $endTime.' 23:59:59');
        }
    }

    /**
     * Filter by status.
     */
    protected function applyAmountFilter(Builder $instance, array $filters = []): void
    {
        if ($minAmount = Arr::get($filters, 'start_amount')) {
            $instance->where('amount', '>=', $minAmount);
        }

        if ($maxAmount = Arr::get($filters, 'end_amount')) {
            $instance->where('amount', '<=', $maxAmount);
        }
    }
}
