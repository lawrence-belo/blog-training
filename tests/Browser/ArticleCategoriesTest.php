<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ArticleCategoriesTest extends DuskTestCase
{
    public static $category;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // first we add a category (we'll use a name starting with 'A' so that it appears on the top of the page)
        self::$category = 'AAATestCategory'.uniqid();
    }

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
            $browser->visit('/categories')
                ->waitForText('Category')
                ->assertSeeIn('h3', 'Categories')
                ->type('new_category_name', self::$category)
                ->click('#add_category');

            // verify category has been successfully added
            $browser->waitForText('Category ' . self::$category . ' has been successfully added!');
            $browser->assertSee('Category ' . self::$category . ' has been successfully added!');
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
            $category = 'books';

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

    /**
     * Edit Category invalid case: blank category name
     *
     * @depends testAddCategory
     * @throws \Throwable
     */
    public function testEditCategoryBlankName()
    {
        $this->browse(function (Browser $browser) {
            // refresh page -> edit the category created in the previous tests
            $browser->visit('/categories')
                ->type('table tr:nth-child(1) td input[type="text"]', '')
                ->click('table tr:nth-child(1) td .update_category');

            // verify no status messages appear
            $browser->assertMissing('div.alert');
        });
    }

    /**
     * Add Category invalid case: non unique category name
     *
     * @throws \Throwable
     */
    public function testEditCategoryNonUniqName()
    {
        $this->browse(function (Browser $browser) {
            $category = 'books';

            $browser->visit('/categories')
                ->waitForText('Category')
                ->type('table tr:nth-child(1) td input[type="text"]', $category)
                ->click('table tr:nth-child(1) td .update_category');

            // verify category has been successfully added
            $browser->waitForText('The category name has already been taken.');
            $browser->assertSee('The category name has already been taken.');
        });
    }

    /**
     * Edit article category valid case
     *
     * @throws \Throwable
     */
    public function testEditCategoryName()
    {
        $this->browse(function (Browser $browser) {
            $category = 'AAATestCategory' . uniqid();

            $browser->visit('/categories')
                ->waitForText('Category')
                ->type('table tr:nth-child(1) td input[type="text"]', $category)
                ->click('table tr:nth-child(1) td .update_category');

            // verify category has been successfully added
            $browser->waitForText('Category ' . self::$category . ' has been changed to ' . $category . '.');
            $browser->assertSee('Category ' . self::$category . ' has been changed to ' . $category . '.');

            // update category name for the next test
            self::$category = $category;
        });
    }

    /**
     * Delete category test
     * Create and delete a category
     *
     * @depends testEditCategoryName
     * @throws \Throwable
     */
    public function testDeleteCategory()
    {
        $this->browse(function (Browser $browser) {
            // delete the new user
            $browser->click('table tr:nth-child(1) td .delete_category');

            // verify delete has been successful
            $browser->waitForText('Category ' . self::$category . ' has been successfully deleted!');
            $browser->assertSee('Category ' . self::$category . ' has been successfully deleted!');

            // reload page
            $browser->visit('/categories');
            // verify the user no longer appears on the list
            $browser->assertDontSee(self::$category);
        });
    }
}

