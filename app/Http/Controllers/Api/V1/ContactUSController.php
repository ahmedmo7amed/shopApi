<?php
namespace App\Http\Controllers\APi\V1;

use App\Http\Controllers\Controller;
use App\Mail\ContactUsMailer;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactUSController extends Controller
{
        public function contactUSPost(Request $request)
        {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'subject' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            $contactMessage = ContactUs::create($validated);

            // Send email
            Mail::to('info@alsalamtank.com')->send(new ContactUsMailer($contactMessage));

            return back()->with('success', 'تم إرسال الرسالة بنجاح!');
        }
}
