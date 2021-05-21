<?php


namespace App\Providers;
use App\Models\Contact;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;

class ContactsServiceProvider
{
    public function SearchById(int $contact_id) {
        $contact = Contact::find($contact_id);
        if (!$contact) {
            throw new ModelNotFoundException('Contact not found by ID ' . $contact_id);
        }
        if ($contact->user_id !== Auth::id()) {
            throw new UnauthorizedException('Action not authorized on contact ID ' . $contact_id);
        }
        return $contact;
    }
}
