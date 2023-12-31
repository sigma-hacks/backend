<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $created_user_id
 * @property int $company_id
 * @property int $name
 * @property int $amount
 */
class CardTariff extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'card_tariffs';

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
        'name',
        'amount',
        'conditions',
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
        'name' => 'string',
        'amount' => 'integer',
        'conditions' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Relationship for getting createdUser
     */
    public function created_user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_user_id');
    }

    /**
     * Relationship for getting Company
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

    const CREATING_RULES = [
        'company_id' => 'required|integer|exists:companies,id',
        'name' => 'required|string',
        'amount' => 'integer',
    ];

    const UPDATING_RULES = [
        'name' => 'string',
        'amount' => 'integer',
    ];
}
