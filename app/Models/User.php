<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function contacts()
    {
        return $this->hasMany('App\Models\Contact');
    }

    public function contactsSharedWithMe()
    {
        return $this->hasManyThrough(
            Contact::class, Share::class,
            'user_id', 'id', 'id', 'contact_id'
        );
    }

    public function mySharedContacts()
    {
        /*
         * select contacts.*, shares.user_id as shared_to from contacts join shares on contacts.id = shares.contact_id where contacts.user_id = 1
         * select contacts.*, users.name as shared_to from contacts join shares on contacts.id = shares.contact_id left join users on users.id = shares.user_id where contacts.user_id = 1
        */
        $userId = Auth::id();
        $contacts = DB::table('contacts')
            ->join('shares', 'contacts.id', '=', 'shares.contact_id')
            ->leftJoin('users', 'users.id', '=', 'shares.user_id')
            ->where('contacts.user_id', $userId)
            ->select('contacts.*', 'users.name as sharedToName', 'users.id as sharedToId');
        return $contacts;
    }

}
