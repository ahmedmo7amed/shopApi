<?php

namespace App\Filament\Resources\ContactUsResource\Pages;

use App\Filament\Resources\ContactUsResource;
use App\Mail\ContactUsMailer;
use App\Models\ContactUs;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;

class CreateContactUs extends CreateRecord
{
    protected static string $resource = ContactUsResource::class;
    // هذه الطريقة ستُنفذ عند إرسال النموذج
    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // إنشاء السجل في قاعدة ابيانات
        $contactUs = ContactUs::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'subject' => $data['subject'],
            'message' => $data['message'],
        ]);

        // إرسال البريد الإلكتروني بعد حفظ البيانات (اختياري)
        Mail::send('email', [
            'name' => $data['name'],
            'email' => $data['email'],
            'user_message' => $data['message']
        ], function($message) use ($data) {
            $message->from($data['email']);
            $message->to(env('CONTACT_EMAIL'));  // تأكد من أنك قد قمت بتعريف هذا المتغير في .env
            $message->subject('Contact Form Submission');
        });

        return $contactUs;
    }
    protected function afterCreate(): void
    {
        // Send email
        Mail::to('your@admin-email.com')->send(
            new ContactUsMailer($this->record)
        );
    }
}
