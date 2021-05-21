<?php

namespace App\Http\Controllers;

use App\Models\Share;
use App\Models\User;
use App\Providers\ContactsServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class ShareController extends Controller
{
    private $contactService;
    public function __construct(ContactsServiceProvider $contactsServiceProvider)
    {
        $this->contactService = $contactsServiceProvider;
    }

    public function listUsers(int $contact_id)
    {
        //list all users and status if $contact_id is shared with them
        $contact = $this->contactService->SearchById($contact_id);
        $users = User::where('id', '<>', Auth::id())->get();
        foreach ($users as &$user) {
            $user->contactShared = false;
            $share = Share::where('contact_id', $contact_id)->where('user_id', $user->id)->first();
            if ($share) {
                $user->contactShared = true;
            }
        }
        return view('share.users', ['contact' => $contact, 'users' => $users]);

    }

    public function shareContact(int $contact_id, int $user_id)
    {
        $contact = $this->contactService->SearchById($contact_id);
        $user = User::find($user_id);

        if (!$user) {
            throw new ModelNotFoundException('User not found');
        }

        $share = Share::where('contact_id', $contact_id)->where('user_id', $user_id)->first();

        $statusMessage = 201;

        if ($share) {
            $statusMessage = 200;
        } else {
            $share = new Share();
            $share->contact_id = $contact_id;
            $share->user_id = $user_id;
            $share->save();
        }

        return response()->json($share, $statusMessage);
    }

    public function unshareContact(int $contact_id, int $user_id)
    {
        $share = Share::where('contact_id', $contact_id)->where('user_id', $user_id)->first();

        if (!$share) {
            throw new ModelNotFoundException('Share not found');
        }
        $share->delete();

        return response()->json(['deleted' => true, 'contact_id' => $contact_id, 'user_id' => $user_id]);
    }

}
