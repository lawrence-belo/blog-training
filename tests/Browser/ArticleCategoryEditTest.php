<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ArticleCategoryEditTest extends DuskTestCase
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
     * Edit Category invalid case: blank category name
     *
     * @throws \Throwable
     */
    public function testEditCategoryBlankName()
    {
        $this->browse(function (Browser $browser) {
            $category = 'testing';

            // create a category 1st
            $browser->visit('/categories')
                ->waitForText('Category')
                ->assertSeeIn('h3', 'Categories')
                ->type('new_category_name', $category)
                ->click('#add_category');

            // refresh page -> edit the category
            $browser->visit('/categories')
                ->type('table tr:nth-child(2) td input[type="text"]', '')
                ->click('table tr:nth-child(2) td .update_category');

            // verify no status messages appear
            $browser->assertMissing('div.alert');
        });
    }

    /**
     * Add Category
     *
     * @throws \Throwable
     */
    public function testEditCategory()
    {
        $this->browse(function (Browser $browser) {
            $category = 'testing'.uniqid();

            $browser->visit('/categories')
                ->waitForText('Category');

            $old_category = $browser->value('table tr:nth-child(2) td input[type="text"]');
            // edit the category
            $browser->type('table tr:nth-child(2) td input[type="text"]', $category)
                ->click('table tr:nth-child(2) td .update_category');

            // verify category has been successfully added
            $browser->waitForText('Category ' . $old_category . ' has been changed to ' . $category . '.');
            $browser->assertSee('Category ' . $old_category . ' has been changed to ' . $category . '.');
        });
    }

    /**
     * Edit Category invalid case: non unique category name
     *
     * @throws \Throwable
     */
    public function testEditCategoryNonUniqName()
    {
        $this->browse(function (Browser $browser) {
            $category = 'testing';

            $browser->visit('/categories')
                ->waitForText('Category');

            // edit the category
            $browser->type('table tr:nth-child(2) td input[type="text"]', $category)
                ->click('table tr:nth-child(2) td .update_category');

            // verify category has been successfully added
            $browser->waitForText('The category name has already been taken.');
            $browser->assertSee('The category name has already been taken.');
        });
    }
}
