<?php

namespace App\Repositories;

use App\Models\AdminUser;
use App\Models\AdminUserLevel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class AdminUserRepository extends BaseEloquentRepository
{
    protected $modelName = AdminUser::class;

    /**
     * @return mixed
     */
    public function getLevels()
    {
        return AdminUserLevel::pluck('name', 'id')->toArray();
    }

    /**
     * {@inheritDoc}
     */
    protected function applyFilters(Builder $instance, array $filters = []): void
    {
        $this->applyIdFilter($instance, $filters);
        $this->applyNameFilter($instance, $filters);
        $this->applyEmailFilter($instance, $filters);
        $this->applyLevelFilter($instance, $filters);
        $this->applyStatusFilter($instance, $filters);
    }

    /**
     * Filter by id.
     */
    protected function applyIdFilter(Builder $instance, array $filters): void
    {
        if ($id = Arr::get($filters, 'id')) {
            $instance->where('admin_users.id', $id);
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
                    $searchString = '%'.$name.'%';
                    $instance->where('admin_users.name', 'like', $searchString);
                }
            });
        }
    }

    /**
     * Filter by email.
     */
    protected function applyEmailFilter(Builder $instance, array $filters): void
    {
        if ($email = Arr::get($filters, 'email')) {
            $searchString = '%'.$email.'%';
            $instance->where('admin_users.email', 'like', $searchString);
        }
    }

    /**
     * Filter by level.
     */
    protected function applyLevelFilter(Builder $instance, array $filters): void
    {
        if (Arr::get($filters, 'level')) {
            $levels = AdminUserLevel::getLevels();

            $instance->whereIn('level', $levels);
        }
    }

    /**
     * Filter by status.
     */
    protected function applyStatusFilter(Builder $instance, array $filters): void
    {
        if (Arr::get($filters, 'status')) {
            $levels = AdminUserLevel::getLevels();

            $instance->whereIn('level', $levels);
        }
    }
}
