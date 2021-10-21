<?php

namespace Starmoozie\MenuPermission\app\Models;

use Starmoozie\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Starmoozie\MenuPermission\app\Traits\ClearsResponseCache;

class Permission extends Model
{
    use CrudTrait, SoftDeletes, Cachable, ClearsResponseCache;
    use Traits\OrderingBy;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'permission';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'note'
    ];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function menu()
    {
        return $this->belongsToMany(Menu::class)->withPivot(['id']);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */

    public function setNameAttribute($var)
    {
        $this->attributes['name'] = toUpper($var);
    }
}
