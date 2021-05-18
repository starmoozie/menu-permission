<?php

namespace Starmoozie\MenuPermission\app\Models\Traits;

/**
 * 
 */
trait OrderingBy
{
    public function scopeOrderByName($query)
    {
        return $query->orderBy('name');
    }
}
