<?php

namespace App\Repositories;

use App\Models\ApiLog;
use App\Models\ApiLogStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ApiLogRepository extends BaseEloquentRepository
{
    protected $modelName = ApiLog::class;

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return ApiLogStatus::pluck('name', 'id')->toArray();
    }

    /**
     * Return the count and total amount of successful payment requests.
     *
     * @param int $workspaceId
     *
     * @return mixed
     *
     * @throws Exception
     */
    // public function getCountAndTotalAmount(array $filters): array
    // {
    //     $oApiLogs = ApiLog::select('order_id')
    //         ->selectRaw('MIN(created_at) AS cb_created_at')
    //         ->where('status_type', 'like', 'confirmed')
    //         ->where('result', '1')
    //         ->groupBy('order_id');

    //     $oOrder = DB::table('new_orders')
    //         ->joinSub($oApiLogs, 'cb', function ($join) {
    //             $join->on('cb.order_id', '=', 'new_orders.id');
    //         })
    //         ->where(function ($query) use ($filters) {
    //             if (in_array('cb_start_time', $filters)) {
    //                 $query->where('cb.cb_created_at', '>=', $filters['cb_start_time'].'00:00:00');
    //             }
    //             if (in_array('cb_end_time', $filters)) {
    //                 $query->where('cb.cb_created_at', '<=', $filters['cb_end_time'].' 23:59:59');
    //             }
    //         })
    //         ->select(DB::raw('count(id) as count_number, sum(`callback_amount`) as sum_number'))
    //         ->first();

    //     $nCount = $oOrder->count_number;
    //     $nTotal = $oOrder->sum_number ?? 0;

    //     return [$nCount, $nTotal];
    // }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applyIdFilter($instance, $filters);
        $this->applyResponseFilter($instance, $filters);
        $this->applyAmountFilter($instance, $filters);

        $this->applyTimeFilter($instance, $filters);
        $this->applyStatusFilter($instance, $filters);
        $this->applyPaymentRequestUniqueCodeFilter($instance, $filters);
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
     * Filter by response.
     */
    protected function applyResponseFilter(Builder $instance, array $filters): void
    {
        if ($response = Arr::get($filters, 'response')) {
            $instance->where('response', 'like', '%'.$response.'%');
        }
    }

    /**
     * Filter by amount.
     */
    protected function applyAmountFilter(Builder $instance, array $filters): void
    {
        if ($amount = Arr::get($filters, 'amount')) {
            $instance->where('send_json', 'like', '%"amount":"'.$amount.'"%')
                ->orWhere('send_json', 'like', '%"amount":'.$amount.'%');
        }

        if ($minAmount = Arr::get($filters, 'start_amount')) {
            $instance->where('response', 'like', '%"amount":"'.$minAmount.'"%');
                // ->orWhere('send_json', 'like', '%"amount":'.$amount.'%');
            $instance->where('amount', '>=', $minAmount);
        }

        if ($maxAmount = Arr::get($filters, 'end_amount')) {
            $instance->where('amount', '<=', $maxAmount);
        }
    }

    /**
     * Filter by date and time.
     */
    protected function applyTimeFilter(Builder $instance, array $filters): void
    {
        if ($startTime = Arr::get($filters, 'start_date')) {
            $instance->where('created_at', '>=', $startTime.' 00:00:00');
        }

        if ($endTime = Arr::get($filters, 'end_date')) {
            $instance->where('created_at', '<=', $endTime.' 23:59:59');
        }
    }

    /**
     * Filter by status.
     */
    protected function applyStatusFilter(Builder $instance, array $filters = []): void
    {
        if ($statuses = Arr::get($filters, 'statuses')) {
            $instance->whereIn('status_id', array_map('intval', $statuses));
        }
    }

    /**
     * Filter by payment request unique code.
     */
    protected function applyPaymentRequestUniqueCodeFilter(Builder $instance, array $filters): void
    {
        if ($code = Arr::get($filters, 'payment_request_unique_code')) {
            $instance->select('api_logs.*')
                ->leftJoin('payment_requests', 'api_logs.payment_request_id', '=', 'payment_requests.id')
                ->where('payment_requests.payment_request_unique_code', $code);
            // $instance->where('payment_request_unique_code', 'like', '%'.$code.'%');
        }
    }

    /**
     * Filter by expid.
     */
    protected function applyPaymentRequestIdFilter(Builder $instance, array $filters = []): void
    {
        if ($expid = Arr::get($filters, 'expid')) {
            $instance->where('send_json', 'like', '%"expid":"'.$expid.'"%')
                ->orWhere('exp_id', '=', $expid);
        }
    }
}
