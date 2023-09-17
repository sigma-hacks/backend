<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BusRouteStation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bus_route_stations';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The date format for table
     *
     * @var string
     */
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_active',
        'company_id',
        'bus_route_id',
        'name',
        'price',
        'sort',
        'distance',
        'map_lat',
        'map_lng',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [

    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'is_active' => 'boolean',
        'company_id' => 'integer',
        'bus_route_id' => 'integer',
        'name' => 'string',
        'price' => 'integer',
        'sort' => 'integer',
        'distance' => 'integer',
        'map_lat' => 'integer',
        'map_lng' => 'integer',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    const CREATING_RULES = [
        'is_active' => 'boolean',
        'company_id' => 'required|integer|exists:companies,id',
        'bus_route_id' => 'required|integer|exists:bus_routes,id',
        'name' => 'required|string',
        'price' => 'required|integer',
        'sort' => 'required|integer',
        'distance' => 'required|integer',
        'map_lat' => 'required|integer',
        'map_lng' => 'required|integer',
    ];

    const UPDATING_RULES = [
        'is_active' => 'boolean',
        'name' => 'required|string',
        'price' => 'required|integer',
        'sort' => 'required|integer',
        'distance' => 'required|integer',
        'map_lat' => 'required|integer',
        'map_lng' => 'required|integer',
    ];

    /**
     * Relationship for getting Company
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * Relationship for getting BusRoute
     */
    public function busRoute(): HasOne
    {
        return $this->hasOne(BusRoute::class, 'id', 'bus_route_id');
    }
}
