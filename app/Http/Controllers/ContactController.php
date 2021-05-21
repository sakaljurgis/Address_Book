<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Share;
use App\Providers\ContactsServiceProvider;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    private $contactService;

    public function __construct(ContactsServiceProvider $contactService)
    {
        $this->middleware('auth');
        $this->contactService = $contactService;
    }

    public function one(int $id = null)
    {
        if ($id) {
            $contact = $this->contactService->SearchById($id);
            return view('contacts.add_or_edit', ['id' => $id, 'contact' => $contact]);
        }
        return view('contacts.add_or_edit', ['id' => $id]);
    }

    public function createOrUpdate(int $id = null)
    {
        if ($id) {
            $contact = $this->contactService->SearchById($id);
        } else {
            $contact = new Contact();
        }

        $result = $this->validate(request(), [
            'name' => 'required',
            'email' => 'nullable|email',
            'phone' => 'nullable',
        ]);

        $contact->name = $result['name'];
        $contact->email = $result['email'];
        $contact->phone = $result['phone'];
        $contact->user_id = Auth::id();
        $contact->save();

        $responseStatus = $id ? 200 : 201;

        return response()->json($contact, $responseStatus);
    }

    public function delete(int $id)
    {
        $contact = $this->contactService->SearchById($id);
        $contact->delete();
        //delete shares
        Share::where('contact_id', $id)->delete();

        return response()->json(['deleted' => true, 'id' => $id]);
    }
}
