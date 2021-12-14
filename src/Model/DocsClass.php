<?php

declare (strict_types=1);
namespace App\Plugins\Docs\src\Model;

use App\Model\Model;
use App\Plugins\User\src\Models\User;
use Carbon\Carbon;

/**
 * @property int $id 
 * @property string $name 
 * @property string $icon 
 * @property string $quanxian 
 * @property string $user_id 
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class DocsClass extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'docs_class';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','icon','quanxian','user_id','created_at','updated_at'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'created_at' => 'datetime', 'updated_at' => 'datetime'];

    public function user(): \Hyperf\Database\Model\Relations\BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}