<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserLoginTest extends DuskTestCase
{
    /**
     * Test invalid login no username
     *
     * @return void
     */
    public function testLoginNoUsername()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', '')
                ->type('password', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the login page
                ->assertSee('Login');
        });
    }

    /**
     * Test invalid login no password
     *
     * @return void
     */
    public function testLoginNoPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', 'testuser1')
                ->type('password', '')
                ->click('button[type="submit"]')

                // we're still on the login page
                ->assertSee('Login');
        });
    }

    /**
     * Test invalid login credentials
     *
     * @return void
     */
    public function testLoginInvalidCredentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', 'anonymous')
                ->type('password', 'anonymous')
                ->click('button[type="submit"]')

                // we're still on the login page
                ->waitForText('These credentials do not match our records.')
                ->assertSee('These credentials do not match our records.');
        });
    }

    /**
     * Test normal login
     *
     * @return void
     */
    public function testLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->assertSee('Login')
                    ->assertSee('Username')
                    ->assertSee('Password')
                    ->type('username', 'testuser1')
                    ->type('password', 'abcd1234')
                    ->click('button[type="submit"]')
                    ->waitForText('Articles')

                    // on successful login the login buttons disappear
                    ->assertDontSee('Login');
        });
    }
}
