<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'type',
        'value',
        'expery_date'
    ];

    public function firstWithExperyDate($name, $userId)
    {
        return $this->whereName($name)->whereDoesntHave('users', fn ($q) => $q->where('users.id', $userId))
            ->whereDate('expery_date', '>=', Carbon::now())->first();
    }
}
