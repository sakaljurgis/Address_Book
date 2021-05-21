<?php


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SharesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $user = Auth::user();
        $contactsSharedWithMe = $user->contactsSharedWithMe()->get();
        $mySharedContacts = $user->mySharedContacts()->get();
        return view(
            'contacts.shares',
            [
                'title' => 'Shared Contacts',
                'contactsSharedWithMe' => $contactsSharedWithMe,
                'mySharedContacts' => $mySharedContacts,
            ]
        );
    }
}
