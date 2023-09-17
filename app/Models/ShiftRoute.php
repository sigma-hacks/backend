<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Validation\Rule;

class ShiftRoute extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shift_routes';

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
        'bus_router_id',
        'shift_id',
        'employer_id',
        'vehicle_number',
        'pos_lat',
        'pos_lng',
        'started_at',
        'finished_at'
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
        'bus_router_id' => 'integer',
        'shift_id' => 'integer',
        'employer_id' => 'integer',
        'vehicle_number' => 'string',
        'pos_lat' => 'float',
        'pos_lng' => 'float',
        'started_at' => 'datetime:Y-m-d H:i:s',
        'finished_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public function shift(): HasOne
    {
        return $this->hasOne(Shift::class, 'id', 'shift_id');
    }

    public function employer(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'employer_id');
    }

    public function getCreatingRules(): array
    {
        return [
            'shift_id' => 'required|integer|exists:shifts,id',
            'employer_id' => ['required', 'integer', Rule::exists('users', 'id')->where(fn($query) => $query->where('role_id', '=', User::ROLE_EMPLOYEE))],
            'vehicle_number' => 'required|string',
            'pos_lat' => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'pos_lng' => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'started_at' => 'date_format:Y-m-d H:i:s',
            'finished_at' => 'date_format:Y-m-d H:i:s'
        ];
    }

    public function getUpdatingRules(): array
    {
        return [
            'vehicle_number' => 'required|string',
            'pos_lat' => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'pos_lng' => ['numeric', 'regex:/^\d+(\.\d{1,2})?$/'],
            'started_at' => 'date_format:Y-m-d H:i:s',
            'finished_at' => 'date_format:Y-m-d H:i:s'
        ];
    }

}
