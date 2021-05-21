<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ContactsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $user = Auth::user();
        $contacts = $user->contacts()->get();
        $contactsSharedWithMe = $user->contactsSharedWithMe()->get();
        return view('contacts.all', [
                'title' => 'Contacts',
                'contacts' => $contacts,
                'contactsSharedWithMe' => $contactsSharedWithMe,
            ]
        );
    }

}
