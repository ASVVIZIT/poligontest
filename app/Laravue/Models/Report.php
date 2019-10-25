<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @property integer $user_id
 * @property integer $order_id
 * @property integer $report_id
 * @property date $created_at
 * @property date $updated_at
 * @property date $deleted_at
 * @property string $title
 * @property float $price
 *
 * @method static Order create(array $order)
 * @package App
 */

class Report extends Model
{

    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'order_id',
        'report_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'title',
        'price',
    ];

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        foreach ($this->roles  as $role) {
            if ($role->isAdmin()) {
                return true;
            }
        }

        return false;
    }
}
