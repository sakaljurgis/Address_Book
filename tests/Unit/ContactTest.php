<?php

namespace Tests\Unit;

use App\Http\Controllers\ContactController;
use App\Models\Contact;
use App\Models\User;
use App\Providers\ContactsServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\UnauthorizedException;
use Tests\TestCase;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    public function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create([
            'id' => 1,
            'name' => 'Test',
            'email' => 'test@test.com',
            'password' => bcrypt('123456')
        ]);

        $this->user = $user;

        $contact = new Contact();
        $contact->name = "Some Name";
        $contact->user_id = 1;
        $contact->id = 1;
        $contact->save();

        $contact = new Contact();
        $contact->name = "Contact Name";
        $contact->user_id = 2;
        $contact->id = 2;
        $contact->save();
    }

    public function test_not_found_contact_returns_exception()
    {
        $this->actingAs($this->user);
        $this->expectException(ModelNotFoundException::class);
        $contactController = $this->app->make(ContactController::class);
        $contactController->one(3);
    }

    public function test_user_access_not_own_contact_returns_exception()
    {
        $this->actingAs($this->user);
        $this->expectException(UnauthorizedException::class);
        $contactController = $this->app->make(ContactController::class);
        $contactController->one(2);
    }

    public function test_contact_name_change_saved_to_db()
    {
        $this->actingAs($this->user);
        $contactsService = $this->app->make(ContactsServiceProvider::class);
        $contact = $contactsService->searchById(1);

        $contact->name = "New Name";
        $contact->save();

        $contact_changed = $contactsService->searchById(1);

        $this->assertEquals("New Name", $contact_changed->name);

    }

}
