<?php

namespace Starmoozie\MenuPermission\app\Models;

use Starmoozie\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Starmoozie\MenuPermission\app\Traits\ClearsResponseCache;

class Role extends Model
{
    use CrudTrait, HasJsonRelationships, SoftDeletes, Cachable, ClearsResponseCache;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'role';
    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'access'
    ];
    protected $casts = [
        'access' => 'array'
    ];
    // protected $primaryKey = 'id';
    // public $timestamps = false;
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

    public function menuPermission()
    {
       return $this->belongsToJson(MenuPermission::class, 'access');
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

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = toUpper($value);
    }
}
