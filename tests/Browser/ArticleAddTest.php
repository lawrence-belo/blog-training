<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Facebook\WebDriver\WebDriverBy;

class ArticleAddTest extends DuskTestCase
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
        $body->sendKeys($text);
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
                ->type('blog_title', ' ')
                ->select('category', 'books')
                ->type('slug', 'sample_blog_article')
                ->click('#save_article')
                ->waitForText('The blog title field is required.')
                ->assertSee('The blog title field is required.');
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
                ->type('blog_title', 'Sample Blog')
                ->select('category', 'books')
                ->type('slug', ' ')
                ->click('#save_article')
                ->waitForText('The slug field is required.')
                ->assertSee('The slug field is required.');
        });
    }

    /**
     * Create article invalid case: no slug
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
                ->type('blog_title', 'Sample Blog')
                ->select('category', 'books')
                ->type('slug', ' ')
                ->click('#save_article')
                ->waitForText('The blog contents field is required.')
                ->assertSee('The blog contents field is required.');
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
                ->type('blog_title', 'Sample Blog')
                ->select('category', 'books')
                ->type('slug', 'sample_blog_article');

            $this->typeInCKEditor('#cke_blog_contents iframe', $browser, 'Lorem ipsum dolor sit amet, eros nemore cotidieque mei ei.');

            $browser->click('#save_article')
                ->waitForText('Blog post Sample Blog has been successfully saved!')
                ->assertSee('Sample Blog')
                ->assertSee('Test User') // this is the first name and last name of our demo user
                ->assertSee('books')
                ->assertSee('Lorem ipsum dolor sit amet, eros nemore cotidieque mei ei.');
        });
    }
}
