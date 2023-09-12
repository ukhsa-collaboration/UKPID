<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Illuminate\Support\Facades\Hash;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AuthTest extends DuskTestCase
{
    use DatabaseTruncation;

    private const TEST_USER_EMAIL = 'test@example.com';

    private const TEST_USER_PASSWORD = 'testpassword';

    private function getTestUser(): User
    {
        return User::factory()->create([
            'email' => self::TEST_USER_EMAIL,
            'password' => Hash::make(self::TEST_USER_PASSWORD),
        ]);
    }

    public function test_that_the_login_page_logs_the_user_in(): void
    {
        $user = $this->getTestUser();

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(route('login'))
                ->assertSee('Log in')
                ->value('@email', self::TEST_USER_EMAIL)
                ->value('@password', self::TEST_USER_PASSWORD)
                ->responsiveScreenshots('login')
                ->click('@submit')
                ->assertAuthenticatedAs($user);
        });
    }

    public function test_that_the_user_can_request_a_password_reset(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit(route('login'))
                ->assertSeeLink('Forgot your password?')
                ->clickLink('Forgot your password?')
                ->assertSee('Recover your account')
                ->value('@email', self::TEST_USER_EMAIL)
                ->responsiveScreenshots('forgot-password')
                ->click('@submit')
                ->assertSee('If the email provided is of a registered user, a password reset link has been sent.')
                ->assertSee('Log in');
        });
    }

    public function test_that_the_user_can_reset_their_password_reset(): void
    {
        // Create a reset token
        $user = $this->getTestUser();
        $token = app('auth.password.broker')->createToken($user);

        $this->browse(function (Browser $browser) use ($token) {
            $browser->visit(route('password.reset', ['token' => $token, 'email' => self::TEST_USER_EMAIL]))
                ->assertSee('Reset Password')
                ->value('@password', 'newpassword1')
                ->value('@password_confirmation', 'newpassword1')
                ->responsiveScreenshots('reset-password')
                ->storeConsoleLog('test2')
                ->click('@submit')
                ->assertSee('Your password has been reset.');
        });
    }
}
