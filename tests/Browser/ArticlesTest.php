<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Facebook\WebDriver\WebDriverBy;

class ArticlesTest extends DuskTestCase
{
    /**
     * Utility method for editing in CkEditor
     * from: https://laracasts.com/discuss/channels/testing/dusk-interact-with-ckeditor
     *
     * @param $selector
     * @param $browser
     * @param string $text
     */
    public function typeInCKEditor ($selector, $browser, $text)
    {
        $ckIframe = $browser->elements($selector)[0];
        $browser->driver->switchTo()->frame($ckIframe);
        $body = $browser->driver->findElement(WebDriverBy::xpath('//body'));

        if (empty($text)) {
            $body->clear();
        } else {
            $body->sendKeys($text);
        }
        $browser->driver->switchTo()->defaultContent();
    }

    /**
     * Login as user
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
     * Create article invalid case: no title
     *
     * @throws \Throwable
     */
    public function testCreateArticleNoTitle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('#new_post')
                ->click('#new_post')
                ->waitForText('Create Article')
                ->type('title', ' ')
                ->select('category', '1')
                ->type('slug', 'sample_blog_article')
                ->click('#save_article')
                ->waitForText('The title field is required.')
                ->assertSee('The title field is required.');
        });
    }

    /**
     * Create article invalid case: no slug
     *
     * @throws \Throwable
     */
    public function testCreateArticleNoSlug()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('#new_post')
                ->click('#new_post')
                ->waitForText('Create Article')
                ->type('title', 'Sample Blog')
                ->select('category', '1')
                ->type('slug', ' ')
                ->click('#save_article')
                ->waitForText('The slug field is required.')
                ->assertSee('The slug field is required.');
        });
    }

    /**
     * Create article invalid case: no contents
     *
     * @throws \Throwable
     */
    public function testCreateArticleNoContents()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('#new_post')
                ->click('#new_post')
                ->waitForText('Create Article')
                ->type('title', 'Sample Blog')
                ->select('category', '1')
                ->type('slug', 'test_slug')
                ->click('#save_article')
                ->waitForText('The contents field is required.')
                ->assertSee('The contents field is required.');
        });
    }

    /**
     * Test Create article
     *
     * @throws \Throwable
     */
    public function testCreateArticle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('#new_post')
                ->click('#new_post')
                ->waitForText('Create Article')
                ->type('title', 'Sample Blog')
                ->select('category', '1')
                ->type('slug', 'sample_blog_article');

            $this->typeInCKEditor('#cke_contents iframe', $browser, 'Lorem ipsum dolor sit amet, eros nemore cotidieque mei ei.');

            $browser->click('#save_article')
                ->waitForText('Blog post Sample Blog has been successfully saved!')
                ->assertSee('Sample Blog')
                ->assertSee('Test User') // this is the first name and last name of our demo user
                ->assertSee('books')
                ->assertSee('Lorem ipsum dolor sit amet, eros nemore cotidieque mei ei.');
        });
    }


    /**
     * Edit article invalid case: no title
     *
     * @throws \Throwable
     */
    public function testEditArticleNoTitle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('.update_article')
                ->click('.update_article')
                ->waitForText('Edit Article')
                ->type('title', ' ')
                ->select('category', '1')
                ->type('slug', 'sample_blog_article')
                ->click('#save_article')
                ->waitForText('The title field is required.')
                ->assertSee('The title field is required.');
        });
    }

    /**
     * Edit article invalid case: no slug
     *
     * @throws \Throwable
     */
    public function testEditArticleNoSlug()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('.update_article')
                ->click('.update_article')
                ->waitForText('Edit Article')
                ->type('title', 'Sample Blog')
                ->select('category', '1')
                ->type('slug', ' ')
                ->click('#save_article')
                ->waitForText('The slug field is required.')
                ->assertSee('The slug field is required.');
        });
    }

    /**
     * Edit article invalid case: no contents
     *
     * @throws \Throwable
     */
    public function testEditArticleNoContents()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('.update_article')
                ->click('.update_article')
                ->waitForText('Edit Article')
                ->type('title', 'Sample Blog')
                ->select('category', '1')
                ->type('slug', 'the_value_to_save');

            $this->typeInCKEditor('#cke_contents iframe', $browser, '');

            $browser->click('#save_article')
                ->waitForText('The contents field is required.')
                ->assertSee('The contents field is required.');
        });
    }

    /**
     * Test Edit article
     *
     * @throws \Throwable
     */
    public function testEditArticle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('.update_article')
                ->click('.update_article')
                ->waitForText('Edit Article')
                ->type('title', 'Sample Blog Update')
                ->select('category', '2')
                ->type('slug', 'sample_blog_article');

            $this->typeInCKEditor('#cke_contents iframe', $browser, 'The quick brown fox jumps over the lazy dog.');

            $browser->click('#save_article')
                ->waitForText('Blog post Sample Blog Update has been successfully updated!')
                ->assertSee('Sample Blog Update')
                ->assertSee('Test User') // this is the first name and last name of our demo user
                ->assertSee('toys')
                ->assertSee('The quick brown fox jumps over the lazy dog.');
        });
    }

    /**
     * Test Delete article
     *
     * @throws \Throwable
     */
    public function testDeleteArticle()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/articles')
                ->waitFor('.delete_article')
                ->click('.delete_article')
                ->waitForText('Blog post Sample Blog Update has been successfully deleted!')
                ->assertSee('Blog post Sample Blog Update has been successfully deleted!');
        });
    }
}
