<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserEditTest extends DuskTestCase
{
    /**
     * Verify that all the fields are available
     *
     * @return void
     */
    public function testUserUpdateUITest()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->type('username', 'testuser1')
                ->type('password', 'abcd1234')
                ->click('button[type="submit"]');
            
            $browser->visit('/update_user/3')
                ->assertSee('Username')
                ->assertSee('First Name')
                ->assertSee('Last Name')
                ->assertSee('Password')
                ->assertSee('Confirm Password')
                ->assertSee('Role')
                ->assertSee('Update');
        });
    }

    /**
     * User Edit Invalid case: no username
     *
     * @return void
     */
    public function testUserUpdateNoUsername()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
                ->type('username', '')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Edit User');
        });
    }

    /**
     * User Edit Invalid case: username less than 6 chars
     *
     * @return void
     */
    public function testUserUpdateUsernameLessChars()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
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
     * User Edit Invalid case: no first_name
     *
     * @return void
     */
    public function testUserUpdateNoFirstname()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
                ->type('username', 'Tester1')
                ->type('first_name', '')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Edit User');
        });
    }

    /**
     * User Edit Invalid case: no last name
     *
     * @return void
     */
    public function testUserUpdateNoLastname()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', '')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Edit User');
        });
    }

    /**
     * User Edit Invalid case: no password
     *
     * @return void
     */
    public function testUserUpdateNoPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', '')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Edit User');
        });
    }

    /**
     * User Edit Invalid case: no confirm password
     *
     * @return void
     */
    public function testUserUpdateNoConfirmPassword()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
                ->type('username', 'Tester1')
                ->type('first_name', 'Test')
                ->type('last_name', 'User')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', '')
                ->click('button[type="submit"]')

                // we're still on the registration page
                ->assertSee('Edit User');
        });
    }

    /**
     * User Edit Invalid case: password does not match
     *
     * @return void
     */
    public function testUserUpdatePasswordNotMatch()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
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
     * User Edit Invalid case: password less than 6 chars
     *
     * @return void
     */
    public function testUserUpdatePasswordLessChars()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
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
     * User Edit non unique username
     *
     * @return void
     */
    public function testUserUpdateNonUniqUsername()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/update_user/3')
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
     * User Edit valid case
     *
     * @return void
     */
    public function testUserUpdate()
    {
        $this->browse(function (Browser $browser) {
            $username = 'Tester' . uniqid();
            $browser->visit('/update_user/3')
                ->type('username', $username)
                ->type('first_name', 'UFirstName')
                ->type('last_name', 'ULastName')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // verify we've the data was successfully saved
                ->waitForText('User data successfully updated!')
                ->assertSee('User data successfully updated!')
                ->assertInputValue('username', $username)
                ->assertInputValue('first_name', 'UFirstName')
                ->assertInputValue('last_name', 'ULastName');
        });
    }
}
