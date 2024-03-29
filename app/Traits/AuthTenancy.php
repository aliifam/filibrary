<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait AuthTenancy
{
    public function bootAuthTenancy()
    {
        if (!app()->runningInConsole() && auth()->check()) {
            $isAdmin = auth()->user()->roles->contains(1);
            // static::creating(function ($model) use ($isAdmin) {
            //     // Prevent admin from setting his own id - admin entries are global.
            //     // If required, remove the surrounding IF condition and admins will act as users
            //     // if (!$isAdmin) {
            //     //     $model->created_by_id = auth()->id();
            //     // }
            //     $model->created_by_id = auth()->id(); // This is the line that sets the user_id
            // });
            if (!$isAdmin) {
                static::addGlobalScope('user_id', function (Builder $builder) {
                    $field = sprintf('%s.%s', $builder->getQuery()->from, 'user_id');

                    $builder->where($field, auth()->id())->orWhereNull($field);
                });
            }
        }
    }
}
