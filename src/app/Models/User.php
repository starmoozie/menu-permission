<?php

namespace Starmoozie\MenuPermission\app\Models;

use App\Models\User as Users;

class User extends Users
{
    // to identity parent table name
    protected $table      = 'users';

    private $new_fillable = [
        'role_id'
    ];

    /**
     * New instance to append parent fillable.
     *
     * @param  array  $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        $this->mergeFillable($this->new_fillable);

        parent::__construct($attributes);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
