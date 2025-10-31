<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Mail;
use App\Models\MailRecipient;

class MailSeeder extends Seeder
{
    public function run(): void
    {
        $mails = Mail::factory()->count(20)->create();

        foreach ($mails as $mail) {
            MailRecipient::factory()->count(rand(1, 5))->create([
                'mail_id' => $mail->id,
            ]);
        }
    }
}
