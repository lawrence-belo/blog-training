<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleCategoryDeleteTest extends DuskTestCase
{
    /**
     * Login as user.
     *
     * @return void
     */
    public function testUserLogin()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                ->assertSee('Username')
                ->assertSee('Password')
                ->type('username', 'testuser1')
                ->type('password', 'abcd1234')
                ->click('button[type="submit"]')

                ->assertDontSee('Login');
        });
    }

    /**
     * Delete category test
     * Create and delete a category
     *
     * @throws \Throwable
     */
    public function testDeleteCategory()
    {
        $this->browse(function (Browser $browser) {

            // first we add a category (we'll use a name starting with 'A' so that it appears on the top of the page)
            $category = 'AAATestCategory' . uniqid();
            $browser->visit('/categories')
                ->assertSeeIn('h3', 'Categories')
                ->type('new_category_name', $category)
                ->click('#add_category');


            // delete the new user
            $browser->click('table tr:nth-child(1) td .delete_category');

            // verify delete has been successful
            $browser->waitForText('Category ' . $category . ' has been successfully deleted!');
            $browser->assertSee('Category ' . $category . ' has been successfully deleted!');

            // reload page
            $browser->visit('/categories');
            // verify the user no longer appears on the list
            $browser->assertDontSee($category);
        });
    }
}
