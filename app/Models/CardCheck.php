<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CardCheck extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card_checks';

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
        'employer_id',
        'card_id',
        'company_id',
        'shift_id',
        'shift_route_id',
        'bus_route_id',
        'pos_lat',
        'pos_lng',
        'checked_at',
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
        'employer_id' => 'integer',
        'card_id' => 'integer',
        'company_id' => 'integer',
        'shift_id' => 'integer',
        'shift_route_id' => 'integer',
        'bus_route_id' => 'integer',
        'pos_lat' => 'float',
        'pos_lng' => 'float',
        'checked_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relationship for getting employer
     */
    public function employer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'employer_id');
    }

    /**
     * Relationship for getting Company
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * Relationship for getting bus route
     */
    public function busRoute(): HasOne
    {
        return $this->hasOne(BusRoute::class, 'id', 'bus_route_id');
    }

    /**
     * Relationship for getting shift
     */
    public function shift(): HasOne
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }

    /**
     * Relationship for getting shift
     */
    public function shiftRoute(): HasOne
    {
        return $this->hasOne(ShiftRoute::class, 'id', 'shift_route_id');
    }

    /**
     * Relationship for getting shift
     */
    public function card(): HasOne
    {
        return $this->hasOne(Card::class, 'id', 'card_id');
    }

    const CREATING_RULES = [];

    const UPDATING_RULES = [];

}
