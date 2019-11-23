<?php

namespace App\Laravue\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Order
 *
 * @property integer $user_id
 * @property integer $orderer_id
 * @property integer $executor_id
 * @property string $title
 * @property string $note
 * @property data $deleted_at
 * @property string $content
 * @property string $content_short
 * @property float $sum
 *
 * @method static Order create(array $order)
 * @package App
 */

class Order extends Model
{

    use Notifiable, SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id',
        'orderer_id',
        'executor_id',
        'status',
        'title',
        'note',
        'deleted_at',
        'content',
        'content_short',
        'sum',
    ];

    private $status;

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

    /**
     * Атрибуты, для которых запрещено массовое назначение.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     *
     */
    public function report()
    {
        return $this->hasMany('Report', 'order_id', 'id') ;
    }

    public function user() {
        return $this->belongsTo('User', 'id', 'user_id') ;
    }

}
