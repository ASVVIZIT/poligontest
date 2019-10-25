<?php

namespace App\Laravue\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @property integer $user_id
 * @property integer $order_id  53535345
 * @property integer $report_id
 * @property integer $orderer_id
 * @property integer $executor_id
 * @property DataTime $created_at
 * @property DataTime $updated_at
 * @property DataTime $deleted_at
 * @property string $note sdfsdfdsf
 * @property float $sum 000.00
 *
 * @method static Order create(array $order)
 * @package App
 */

class Order extends Model
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
        'orderer_id',
        'executor_id',
        'created_at',
        'updated_at',
        'deleted_at',
        'note',
        'sum',
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
