<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UsersTest extends DuskTestCase
{
    /**
     * @var string $username to be used throughout the test
     */
    public static $username;

    /**
     * @var string $locator used to locate the update and delete buttons
     */
    public static $locator;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // add a user (we'll use a name starting with 'A' so that it appears on the first page)
        self::$username = 'A0Tester' . uniqid();

        self::$locator = '';
    }

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
     * Test pagination on user list
     * Move 3 pages forward and back
     * Verify content varies in each page
     *
     * @depends testAdminLogin
     * @return void
     */
    public function testPagination()
    {
        $this->browse(function (Browser $browser) {
            $page1_entry = $browser->text('table tr:nth-child(1) td:nth-child(1)');

            // move to page 2
            $browser->click('.pagination li:nth-child(3) a');

            // verify we are on page 2
            $browser->waitFor('.pagination li:nth-child(3) span');
            $browser->assertSeeIn('.pagination li:nth-child(3) span', '2');

            $page2_entry = $browser->text('table tr:nth-child(1) td:nth-child(1)');

            // verify content is different
            $this->assertNotEquals($page1_entry, $page2_entry);

            // move to page 3 using the next button instead of the page number link
            $browser->click('.pagination li:nth-child(8) a');

            // verify we are on page 3
            $browser->waitFor('.pagination li:nth-child(4) span');
            $browser->assertSeeIn('.pagination li:nth-child(4) span', '3');

            $page3_entry = $browser->text('table tr:nth-child(1) td:nth-child(1)');

            // verify content is different
            $this->assertNotEquals($page2_entry, $page3_entry);

            // move back one page using the back button
            $browser->click('.pagination li:nth-child(1) a');

            //verify the content is the same since our last visit to this page
            $page2back_entry = $browser->text('table tr:nth-child(1) td:nth-child(1)');
            $this->assertEquals($page2_entry, $page2back_entry);
        });
    }

    /**
     * Verify that all the fields are available
     *
     * @depends testAdminLogin
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
            $browser->visit('/add_user')
                ->type('username', self::$username)
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                // verify we've logged in and we've are on the list
                ->waitForText('User List')
                ->assertSee(self::$username);
        });
    }

    /**
     * User edit invalid case: no username
     *
     * @depends testRegistration
     * @throws \Throwable
     */
    public function testUserEditNoUsername()
    {
        $this->browse(function (Browser $browser) {
            // locate the new user created in previous test
            for ($i = 1; $i <= 10; $i++) {
                self::$locator = 'table tr:nth-child(' . $i . ')';
                if (self::$username == $browser->text(self::$locator . ' td:nth-child(1)')) {
                    break;
                }
            }
            $update_button = self::$locator . ' td .update_button';

            $browser->visit('/')
                ->click($update_button)
                ->type('username', ' ')
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                ->waitForText('The username field is required.')
                ->assertSee('The username field is required.');
        });
    }

    /**
     * User edit invalid case: no first_name
     *
     * @depends testUserEditNoUsername
     * @throws \Throwable
     */
    public function testUserEditNoFirstName()
    {
        $this->browse(function (Browser $browser) {
            $update_button = self::$locator . ' td .update_button';

            $browser->visit('/')
                ->click($update_button)
                ->type('username', self::$username)
                ->type('first_name', ' ')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                ->waitForText('The first name field is required.')
                ->assertSee('The first name field is required.');
        });
    }

    /**
     * User edit invalid case: no last_name
     *
     * @depends testUserEditNoFirstName
     * @throws \Throwable
     */
    public function testUserEditNoLastName()
    {
        $this->browse(function (Browser $browser) {
            $update_button = self::$locator . ' td .update_button';

            $browser->visit('/')
                ->click($update_button)
                ->type('username', self::$username)
                ->type('first_name', 'aaaa')
                ->type('last_name', ' ')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                ->waitForText('The last name field is required.')
                ->assertSee('The last name field is required.');
        });
    }

    /**
     * User edit invalid case: non matching passwords
     *
     * @depends testUserEditNoLastName
     * @throws \Throwable
     */
    public function testUserEditNonMatchingPasswords()
    {
        $this->browse(function (Browser $browser) {
            $update_button = self::$locator . ' td .update_button';

            $browser->visit('/')
                ->click($update_button)
                ->type('username', self::$username)
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcder1234')
                ->click('button[type="submit"]')

                ->waitForText('The password confirmation does not match.')
                ->assertSee('The password confirmation does not match.');
        });
    }

    /**
     * User edit invalid case: non unique username
     *
     * @depends testUserEditNonMatchingPasswords
     * @throws \Throwable
     */
    public function testUserEditNonUniqueUsername()
    {
        $this->browse(function (Browser $browser) {
            $update_button = self::$locator . ' td .update_button';

            $browser->visit('/')
                ->click($update_button)
                ->type('username', 'testuser1')
                ->type('first_name', 'aaaa')
                ->type('last_name', 'aaaa')
                ->type('password', 'abcd1234')
                ->type('password_confirmation', 'abcd1234')
                ->click('button[type="submit"]')

                ->waitForText('The username has already been taken.')
                ->assertSee('The username has already been taken.');
        });
    }

    /**
     * User edit valid case
     *
     * @depends testUserEditNonUniqueUsername
     * @throws \Throwable
     */
    public function testUserUpdate()
    {
        $this->browse(function (Browser $browser) {
            $update_button = self::$locator . ' td .update_button';

            self::$username = 'A0Tester' . uniqid();
            $browser->visit('/')
                ->click($update_button)
                ->type('username', self::$username)
                ->type('first_name', 'aaaab')
                ->type('last_name', 'aaaab')
                ->click('button[type="submit"]')

                ->waitForText('User data successfully updated!')
                ->assertSee('User data successfully updated!')
                ->assertValue('input[name="username"]', self::$username)
                ->assertValue('input[name="first_name"]', 'aaaab')
                ->assertValue('input[name="last_name"]', 'aaaab');
        });
    }

    /**
     * User delete invalid case
     *
     * @depends testUserUpdate
     * @throws \Throwable
     */
    public function testUserDeleteInvalidId()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/delete_user/1000')
                ->waitForText('Sorry, the page you are looking for could not be found.')
                ->assertSee('Sorry, the page you are looking for could not be found.');
        });
    }

    /**
     * User delete valid case
     *
     * @depends testUserUpdate
     * @throws \Throwable
     */
    public function testUserDelete()
    {
        $this->browse(function (Browser $browser) {
            $delete_button = self::$locator . ' td .delete_button';

            $browser->visit('/')
                ->click($delete_button)

                ->waitForText('User ' . self::$username . ' has been successfully deleted!')
                ->assertSee('User ' . self::$username . ' has been successfully deleted!');
        });
    }
}
