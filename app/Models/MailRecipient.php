<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\MailRecipientStatus;
use App\Models\Scopes\MailRecipientScopes;

class MailRecipient extends Model
{
    use HasFactory, SoftDeletes, MailRecipientScopes;

    protected $fillable = [
        'mail_id',
        'user_id',
        'email',
        'name',
        'status',
        'error_log',
    ];
    protected $casts = [
        'status' => MailRecipientStatus::class,
    ];


    public function mail()
    {
        return $this->belongsTo(Mail::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}