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

    public function testModuleProperties() {
        $module = \Yii::$app->getModule('news');
        $this->assertEquals("news", $module->id, 'Invalid value for module property `ID`');
        $this->assertEquals("/news", $module->baseRoute, 'Invalid value for module property `baseRoute`');
        $this->assertEquals("admin/news/default", $module->defaultController, 'Invalid value for module property `defaultController`');
        $this->assertEquals("@app/views/layouts/main", $module->baseLayout, 'Invalid value for module property `baseLayout`');
        $this->assertEquals("/uploads/news", $module->imagePath, 'Invalid value for module property `imagePath`');
        $this->assertEquals(['ru-RU', 'uk-UA', 'en-US'], $module->supportLocales, 'Invalid value for module property `supportLocales`');

    }

    public function testGetPostUrl()
    {
        $module = \Yii::$app->getModule('news');
        $news = $this->tester->grabFixture('news', 'news2');
        $this->assertEquals($module->baseRoute . '/some-test-news-2', $news->getPostUrl());
    }

    public function testGetPublicUrl()
    {
        $module = \Yii::$app->getModule('news');
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals($module->baseRoute . '/some-test-news-3', $news->url);
    }

    public function testGetImagePath()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals('/uploads/news/Test-news3.jpg', $news->getImage());
    }

    public function testGetImageAbsolutePath()
    {
        $module = \Yii::$app->getModule('news');
        $news = $this->tester->grabFixture('news', 'news3');
        $this->assertEquals($this->tester->getBaseUrl() . $module->imagePath . '/Test-news3.jpg', $news->getImage(true));
    }

    public function testValidateRequiredAttributes()
    {
        $news = new News([
            'name' => null,
            'alias' => 'other-news-test-post',
            'content' => 'Lorem ipsum dolor sit amet'
        ]);
        $this->assertFalse($news->validate(), 'validate incorrect `name` attribute');
        $this->assertArrayHasKey('name', $news->getErrors(), 'Name cannot be blank.');

        $news = new News([
            'name' => null,
            'alias' => null,
            'content' => 'Lorem ipsum dolor sit amet'
        ]);
        $this->assertFalse($news->validate(), 'validate incorrect `alias` attribute');
        $this->assertArrayHasKey('alias', $news->getErrors(), 'Alias cannot be blank.');

        $news = new News([
            'name' => 'Other news test post',
            'alias' => 'other-news-test-post',
            'content' => null
        ]);
        $this->assertFalse($news->validate(), 'validate incorrect `content` attribute');
        $this->assertArrayHasKey('content', $news->getErrors(), 'News text cannot be blank.');
    }

    public function testValidateMinStringLengths()
    {
        $news = new News([
            'name' => 'Lo',
            'alias' => 'lo',
        ]);
        $this->assertFalse($news->validate(), 'validate incorrect string attributes min lengths');
        $this->assertArrayHasKey('name', $news->getErrors(), 'Name should contain at least 3 characters.');
        $this->assertArrayHasKey('alias', $news->getErrors(), 'Alias should contain at least 3 characters.');
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
        $this->assertFalse($news->validate(), 'validate incorrect string attributes max lengths');
        $this->assertArrayHasKey('name', $news->getErrors(), 'Name should contain at most 128 characters.');
        $this->assertArrayHasKey('alias', $news->getErrors(), 'Alias should contain at most 128 characters.');
        $this->assertArrayHasKey('title', $news->getErrors(), 'Title should contain at most 255 characters.');
        $this->assertArrayHasKey('excerpt', $news->getErrors(), 'Excerpt should contain at most 255 characters.');
        $this->assertArrayHasKey('description', $news->getErrors(), 'Description should contain at most 255 characters.');
        $this->assertArrayHasKey('keywords', $news->getErrors(), 'Keywords should contain at most 255 characters.');
        $this->assertArrayHasKey('image', $news->getErrors(), 'Image should contain at most 255 characters.');

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