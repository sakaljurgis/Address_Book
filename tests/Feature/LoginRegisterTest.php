<?php


namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoginRegisterTest extends TestCase
{

    use RefreshDatabase;

    public function test_unauthorized_user_access_contacts_redirects_to_login()
    {

        $response = $this->get('/contacts');
        $response->assertRedirect('/login');

    }

    public function test_redirect_to_contacts_and_logged_in_after_register()
    {
        $response = $this->post('register', [
            'name' => 'Vartotojas',
            'email' => 'vartotojas@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect('/contacts');
        $this->assertTrue(Auth::check());
    }

    function test_redirect_to_contacts_and_logged_in_after_login()
    {

        $user = User::factory()->create([
            'name' => 'Test',
            'email' => 'vartotojas2@gmail.com',
            'password' => bcrypt('123456')
        ]);

        $response = $this->post('login', [
            'email' => 'vartotojas2@gmail.com',
            'password' => '123456'
        ]);

        $response->assertRedirect('/contacts');
        $this->assertTrue(Auth::check());
    }

}
