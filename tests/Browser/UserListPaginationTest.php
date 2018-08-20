<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class UserListPaginationTest extends DuskTestCase
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
     * Test pagination on user list
     * Move 3 pages forward and back
     * Verify content varies in each page
     *
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
}
