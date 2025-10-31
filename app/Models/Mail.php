<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\MailType;
use App\Models\Scopes\MailScopes;

class Mail extends Model
{
    use HasFactory, SoftDeletes, MailScopes;

    protected $fillable = [
        'subject',
        'content',
        'template_key',
        'type',
        'sender_email',
        'variables',
    ];

    protected $casts = [
        'type' => MailType::class,
        'variables' => 'array',
    ];

    public function recipients()
    {
        return $this->hasMany(MailRecipient::class);
    }
}