<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class PasswordMemo extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name', 'url', 'username', 'encrypted_password', 'category', 'notes'
    ];

    public function getDecryptedPasswordAttribute()
    {
        return Crypt::decryptString($this->encrypted_password);
    }
}
