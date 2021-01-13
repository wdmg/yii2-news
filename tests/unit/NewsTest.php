<?php

namespace wdmg\news\tests\unit;

use wdmg\news\models\News;
use wdmg\news\tests\fixtures\NewsFixture;
use wdmg\news\tests\fixtures\LangFixture;

class NewsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures() {
        if (class_exists('\wdmg\translations\models\Languages')) {
            $fixtures['languages'] = [
                'class' => LangFixture::className(),
                'dataFile' => codecept_data_dir() . 'models/languages.php'
            ];
        }

        $fixtures['news'] = [
            'class' => NewsFixture::className(),
            'dataFile' => codecept_data_dir() . 'models/news.php'
        ];

        return $fixtures;
    }

    protected function _before()
    {
        if (class_exists('\wdmg\translations\models\Languages')) {
            \Yii::$app->setModule('translations', [
                'wdmg\translations\Module'
            ]);
        }

        \Yii::$app->setModule('news', [
            'class' => 'wdmg\news\Module'
        ]);
    }

    protected function _after()
    {
    }

    public function testFixturesData()
    {
        $data = $this->tester->grabFixture('news', 'news1');
        $this->assertEquals(1, $data->id);
    }

    public function testGetPostUrl()
    {
        $news = $this->tester->grabFixture('news', 'news2');
        $this->assertEquals('/some-test-news-2', $news->getPostUrl());
    }

    public function testGetPublicUrl()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals('/some-test-news-3', $news->url);
    }

    public function testGetImagePath()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals('/uploads/news/Test-news3.jpg', $news->getImage());
    }

    public function testGetImageAbsolutePath()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals($this->tester->getBaseUrl() . '/uploads/news/Test-news3.jpg', $news->getImage(true));
    }

    public function testValidateRequiredName()
    {
        $news = new News([
            'name' => null,
            'alias' => 'other-news-test-post',
            'content' => 'Lorem ipsum dolor sit amet'
        ]);
        $news->validate();
        $this->assertEquals('Name cannot be blank.', $news->getFirstError('name'));
    }

    public function testValidateRequiredAlias()
    {
        $news = new News([
            'name' => null,
            'alias' => null,
            'content' => 'Lorem ipsum dolor sit amet'
        ]);
        $news->validate();
        $this->assertEquals('Alias cannot be blank.', $news->getFirstError('alias'));
    }

    public function testValidateRequiredContent()
    {
        $news = new News([
            'name' => 'Other news test post',
            'alias' => 'other-news-test-post',
            'content' => null
        ]);
        $news->validate();
        $this->assertEquals('News text cannot be blank.', $news->getFirstError('content'));
    }

    public function testValidateMaxStringLengths()
    {
        $news = new News([
            'name' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.',
            'alias' => 'lorem-ipsum-dolor-sit-amet-consectetuer-adipiscing-elit-sed-diam-nonummy-nibh-euismod-tincidunt-ut-laoreet-dolore-magna-aliqua-erat-volutpat',
            'title' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
            'excerpt' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
            'description' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
            'keywords' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
            'image' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
        ]);
        $news->validate();

        $this->assertEquals('Name should contain at most 128 characters.', $news->getFirstError('name'));
        $this->assertEquals('Alias should contain at most 128 characters.', $news->getFirstError('alias'));
        $this->assertEquals('Title should contain at most 255 characters.', $news->getFirstError('title'));
        $this->assertEquals('Excerpt should contain at most 255 characters.', $news->getFirstError('excerpt'));
        $this->assertEquals('Description should contain at most 255 characters.', $news->getFirstError('description'));
        $this->assertEquals('Keywords should contain at most 255 characters.', $news->getFirstError('keywords'));
        $this->assertEquals('Image should contain at most 255 characters.', $news->getFirstError('image'));

    }

    public function testValidateMinStringLengths()
    {
        $news = new News([
            'name' => 'Lo',
            'alias' => 'lo',
        ]);
        $news->validate();
        $this->assertEquals('Name should contain at least 3 characters.', $news->getFirstError('name'));
        $this->assertEquals('Alias should contain at least 3 characters.', $news->getFirstError('alias'));
    }

    public function testAttemptAddNewPost()
    {
        $news = new News([
            'id' => 7,
            'source_id' => 2,
            'name' => 'Other news test post',
            'alias' => null,
            'image' => null,
            'excerpt' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.',
            'content' => 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat. Duis autem vel eum iriure dolor in hendrerit in vulputate velit esse molestie consequat, vel illum dolore eu feugiat nulla facilisis at vero eros et accumsan et iusto odio dignissim qui blandit praesent luptatum zzril delenit augue duis dolore te feugait nulla facilisi.',
            'title' => 'Other news headline',
            'description' => 'A short description of new test post',
            'keywords' => 'test, news, other',
            'status' => 1,
            'locale' => 'en-US',
            'in_turbo' => 1,
            'in_rss' => 1,
            'in_amp' => 1,
            'in_sitemap' => 1
        ]);

        if ($news->validate()) {
            if ($news->save())
                $this->assertEquals('other-news-test-post', $news->alias);
            else
                $this->fail('Model save failed.');
        } else {
            $this->fail($news->errors);
        }
    }

}