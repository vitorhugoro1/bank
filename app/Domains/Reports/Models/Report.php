<?php

namespace App\Domains\Reports\Models;

use App\Domains\Account\Models\Account;
use App\Domains\Reports\Enums\ReportOperationEnum;
use Database\Factories\ReportFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'operation', 'account_id', 'occurred_at', 'amount', 'balance'
    ];

    protected $casts = [
        'operation' => ReportOperationEnum::class,
        'occurred_at' => 'datetime',
        'amount' => 'integer',
        'balance' => 'integer'
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
        return ReportFactory::new();
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
