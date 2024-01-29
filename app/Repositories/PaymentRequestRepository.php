<?php

namespace App\Repositories;

use App\Models\PaymentRequest;
use App\Models\PaymentRequestStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class PaymentRequestRepository extends BaseEloquentRepository
{
    protected $modelName = PaymentRequest::class;

    /**
     * @return mixed
     */
    public function getStatuses()
    {
        return PaymentRequestStatus::pluck('name', 'id')->toArray();
    }

    public static function getAllStatusesAsArray()
    {
        $retrievedStatuses = PaymentRequestStatus::all();
        $statuses = [];
        foreach ($retrievedStatuses as $item) {
            $statuses[$item['id']] = $item['name'];
        }

        return $statuses;
    }

    /**
     * @return mixed
     */
    // public function store(array $data) // should this be service?
    // {
    //     $this->instance = $this->getNewInstance();
    //     $data['user_order_count'] = $this->countByUser($data['user_code']) + 1;
    //     $data['product_id'] = config('const.ORDER_DIRECT_PRICE_PRODUCT_ID');
    //     $data['callback_sent_at'] = now();

    //     return $this->executeSave($data);
    // }

    /**
     * Return the count of active subscribers
     *
     * @param int $workspaceId
     *
     * @return mixed
     *
     * @throws Exception
     */
    public function getCountAndTotalAmount(array $filters): array
    {
        $instance = $this->getQueryBuilder();
        $this->applyFilters($instance, $filters);
        $nCount = $instance->count();
        $nTotal = $instance->sum('amount');
        return [$nCount, $nTotal];
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applyIdFilter($instance, $filters);
        $this->applyUserCodeFilter($instance, $filters);
        $this->applyPaymentRequestUniqueCodeFilter($instance, $filters);
        $this->applyPaymentCodeFilter($instance, $filters);
        $this->applyNameFilter($instance, $filters);
        $this->applyPayinIdFilter($instance, $filters);

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
     * Filter by user code.
     */
    protected function applyUserCodeFilter(Builder $instance, array $filters): void
    {
        if ($userCode = Arr::get($filters, 'user_code')) {
            // $instance->where('user_code', 'like', '%'.$userCode.'%');
            // DB::table('users')->where('user_code', $userCode);
            $instance->select('payment_requests.*')
                ->leftJoin('users', 'payment_requests.user_id', '=', 'users.id')
                ->where('users.user_code', $userCode);
        }
    }

    /**
     * Filter by payment request unique code.
     */
    protected function applyPaymentRequestUniqueCodeFilter(Builder $instance, array $filters): void
    {
        if ($code = Arr::get($filters, 'payment_request_unique_code')) {
            $instance->where('payment_request_unique_code', 'like', '%'.$code.'%');
        }
    }

    /**
     * Filter by payment code.
     */
    protected function applyPaymentCodeFilter(Builder $instance, array $filters): void
    {
        if ($paymentCode = Arr::get($filters, 'payment_code')) {
            // $instance->where('payment_code', $paymentCode);
            $instance->select('payment_requests.*')
                ->leftJoin('users', 'payment_requests.user_id', '=', 'users.id')
                ->where('users.payment_code', $paymentCode);
        }
    }

    /**
     * Filter by name.
     */
    protected function applyNameFilter(Builder $instance, array $filters): void
    {
        if ($name = Arr::get($filters, 'hurigana')) {
            $names = explode(' ', $name);

            $instance->where(static function (Builder $instance) use ($names) {
                foreach ($names as $name) {
                    $filterString = '%'.$name.'%';
                    $instance->where('new_users.first_name', 'like', $filterString)
                        ->orWhere('new_users.last_name', 'like', $filterString)
                        ->orWhere('new_users.hurigana', 'like', $filterString);
                }
            });
        }
    }

    /**
     * Filter by date and time.
     */
    protected function applyTimeFilter(Builder $instance, array $filters): void
    {
        if ($startDate = Arr::get($filters, 'start_date')) {
            $instance->where('created_at', '>=', $startDate.' 00:00:00');
        }

        if ($endDate = Arr::get($filters, 'end_date')) {
            $instance->where('created_at', '<=', $endDate.' 23:59:59');
        }

        if ($startDate = Arr::get($filters, 'cb_start_date')) {
            $instance->where('callback_sent_at', '>=', $startDate.' 00:00:00');
        }

        if ($endDate = Arr::get($filters, 'cb_end_date')) {
            $instance->where('callback_sent_at', '<=', $endDate.' 23:59:59');
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

    /**
     * Filter by payin id.
     */
    protected function applyPayinIdFilter(Builder $instance, array $filters): void
    {
        if ($payin_id = Arr::get($filters, 'payin_id')) {
            $instance->where('payin_id', $payin_id);
        }
    }

    private function countByUser(int $userCode): int
    {
        return PaymentRequest::where('user_code', $userCode)->count();
    }
}
