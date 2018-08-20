<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleCategoryAddTest extends DuskTestCase
{
    /**
     * Test user login
     *
     * @return void
     */
    public function testUserLogin()
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

    /**
     * Add Category invalid case: blank category name
     *
     * @throws \Throwable
     */
    public function testAddCategoryBlankName()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/categories')
                ->waitForText('Category')
                ->assertSeeIn('h3', 'Categories')
                ->click('#add_category');

            // verify no status messages appear
            $browser->assertMissing('div.alert');
        });
    }

    /**
     * Add Category
     *
     * @throws \Throwable
     */
    public function testAddCategory()
    {
        $this->browse(function (Browser $browser) {
            $category = 'miscellaneous';

            $browser->visit('/categories')
                ->waitForText('Category')
                ->assertSeeIn('h3', 'Categories')
                ->type('new_category_name', $category)
                ->click('#add_category');

            // verify category has been successfully added
            $browser->waitForText('Category ' . $category . ' has been successfully added!');
            $browser->assertSee('Category ' . $category . ' has been successfully added!');
        });
    }

    /**
     * Add Category invalid case: non unique category name
     *
     * @throws \Throwable
     */
    public function testAddCategoryNonUniqName()
    {
        $this->browse(function (Browser $browser) {
            $category = 'miscellaneous';

            $browser->visit('/categories')
                ->waitForText('Category')
                ->assertSeeIn('h3', 'Categories')
                ->type('new_category_name', $category)
                ->click('#add_category');

            // verify category has been successfully added
            $browser->waitForText('The new category name has already been taken.');
            $browser->assertSee('The new category name has already been taken.');
        });
    }
}

