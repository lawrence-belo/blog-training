<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserDeleteTest extends DuskTestCase
{
    /**
     * Login as admin.
     *
     * @return void
     */
    public function testAdminLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', 'testadmin1')
                ->type('password', 'abcd1234')
                ->click('button[type="submit"]')

                // we're still on the login page
                ->waitForText('User List')
                ->assertSee('User List');
        });
    }

    /**
     * Delete user test
     * Create and delete a user
     *
     * @throws \Throwable
     */
    public function testDeleteUser()
    {
        $this->browse(function (Browser $browser) {

            // first we add a user (we'll use a name starting with 'A' so that it appears on the first page)
            $username = 'A0Tester' . uniqid();
            $browser->visit('/add_user')
                ->type('username', $username)
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // verify we see the new user on the list
                ->waitForText('User List')
                ->assertSee($username);

            // locate the new user
            for ($i = 1; $i <= 10; $i++) {
                $locator = 'table tr:nth-child(' . $i . ')';
                if ($username == $browser->text($locator . ' td:nth-child(1)')) {
                    break;
                }
            }

            // Get locator for delete button
            $delete_button = $locator . ' td .delete_button';

            // delete the new user
            $browser->click($delete_button);

            // verify delete has been successful
            $browser->waitForText('User ' . $username . ' has been successfully deleted!');
            $browser->assertSee('User ' . $username . ' has been successfully deleted!');

            // reload page
            $browser->visit('/home');
            // verify the user no longer appears on the list
            $browser->assertDontSee($username);
        });
    }
}
