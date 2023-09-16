<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ServiceDiscount extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'service_discounts';

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
        'created_user_id',
        'company_id',
        'tariff_id',
        'service_id',
        'is_active',
        'description',
        'name',
        'amount',
        'discount_type',
        'started_at',
        'finished_at',
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
        'created_user_id' => 'integer',
        'company_id' => 'integer',
        'tariff_id' => 'integer',
        'service_id' => 'integer',
        'is_active' => 'boolean',
        'description' => 'string',
        'name' => 'string',
        'amount' => 'integer',
        'discount_type' => 'integer',
        'started_at' => 'datetime:Y-m-d H:i:s',
        'finished_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relationship for getting CreatedUser
     *
     * @return HasOne
     */
    public function created_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_user_id');
    }

    /**
     * Relationship for getting company
     *
     * @return HasOne
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    /**
     * Relationship for getting tariff
     *
     * @return HasOne
     */
    public function tariff(): HasOne
    {
        return $this->hasOne(TariffDiscount::class, 'id', 'tariff_id');
    }

    /**
     * Relationship for getting service
     *
     * @return HasOne
     */
    public function service(): HasOne
    {
        return $this->hasOne(CompanyService::class, 'id', 'service_id');
    }
}
