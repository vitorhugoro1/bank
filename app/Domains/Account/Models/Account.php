<?php

namespace App\Domains\Account\Models;

use App\Domains\Account\Enums\AccountType;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Users\Models\User;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Account extends Model
{
    use SoftDeletes, HasFactory;

    protected $table = 'accounts';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'user_id', 'type', 'balance'
    ];

    protected $casts = [
        'type' => AccountType::class,
        'balance' => 'float'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Model $model) {
            $model->setAttribute($model->getKeyName(), Str::uuid());
        });
    }

    protected static function newFactory()
    {
        return AccountFactory::new();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
