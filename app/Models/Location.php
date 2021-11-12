<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Location extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
    ];

    /**
     * @param mixed $value
     * @param null $field
     * @return Location|\Illuminate\Database\Eloquent\Builder|Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $routeContainsString = fn(String $string) => Str::contains(
            Str::lower(Route::current()->uri),
            Str::lower($string),
        );

        // restore
        if ($routeContainsString('restore')) {
            return $this->onlyTrashed()->where($field ?? $this->getRouteKeyName(), $value)->first();
        }

        // force-delete
        if ($routeContainsString('force-delete')) {
            return $this->withTrashed()->where($field ?? $this->getRouteKeyName(), $value)->first();
        }

        return parent::resolveRouteBinding($value, $field);
    }

    /**
     * @return BelongsTo
     */
    public function user(): belongsTo
    {
        return $this->belongsTo(User::class);
    }
}
