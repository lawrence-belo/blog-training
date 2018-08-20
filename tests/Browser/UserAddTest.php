<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserAddTest extends DuskTestCase
{
    public function testAdminLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', 'testadmin1')
                ->type('password', 'abcd1234')
                ->click('button[type="submit"]')
                ->waitForText('User List')

                // on successful login the login buttons disappear
                ->assertSee('Users');
        });
    }
    /**
     * Verify that all the fields are available
     *
     * @return void
     */
    public function testRegistrationUITest()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->assertSee('Username')
                ->assertSee('First Name')
                ->assertSee('Last Name')
                ->assertSee('Password')
                ->assertSee('Confirm Password')
                ->assertSee('Role')
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: no username
     *
     * @return void
     */
    public function testRegistrationNoUsername()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', '')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: username less than 6 chars
     *
     * @return void
     */
    public function testRegistrationUsernameLessChars()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'abc')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->waitForText('The username must be at least 6 characters.')
                ->assertSee('The username must be at least 6 characters.');
        });
    }

    /**
     * Registration Invalid case: no first_name
     *
     * @return void
     */
    public function testRegistrationNoFirstname()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', '')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: no last name
     *
     * @return void
     */
    public function testRegistrationNoLastname()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', '')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: no password
     *
     * @return void
     */
    public function testRegistrationNoPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', '')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: no confirm password
     *
     * @return void
     */
    public function testRegistrationNoConfirmPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', '')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Save');
        });
    }

    /**
     * Registration Invalid case: password does not match
     *
     * @return void
     */
    public function testRegistrationPasswordNotMatch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234qw')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->waitForText('The password confirmation does not match.')
                ->assertSee('The password confirmation does not match.');
        });
    }

    /**
     * Registration Invalid case: password less than 6 chars
     *
     * @return void
     */
    public function testRegistrationPasswordLessChars()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'a1')
                ->type('password_confirmation', 'a1')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->waitForText('The password must be at least 6 characters.')
                ->assertSee('The password must be at least 6 characters.');
        });
    }

    /**
     * Registration non unique username
     *
     * @return void
     */
    public function testRegistrationNonUniqUsername()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/add_user')
                ->type('username', 'testuser1')
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // verify we've logged in and we've are on the list
                ->waitForText('The username has already been taken.')
                ->assertSee('The username has already been taken.');
        });
    }

    /**
     * Registration valid case
     *
     * @return void
     */
    public function testRegistration()
    {
        $this->browse(function (Browser $browser) {
            $username = 'Tester' . uniqid();
            $browser->visit('/add_user')
                ->type('username', $username)
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // verify we've logged in and we've are on the list
                ->waitForText('User List')
                ->assertSee($username);
        });
    }
}
