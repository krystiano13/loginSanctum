<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Token extends Model
{
    use HasFactory;

    /**
     * @var string
    */
    protected $table = 'personal_access_tokens';

    /**
     * @var string
    */
    protected $primaryKey = 'id';

    protected $fillable = [
        'token',
        'expires_at',
        'name',
        'id'
    ];

}
