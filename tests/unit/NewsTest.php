<?php

namespace wdmg\news\tests\unit;

use wdmg\news\tests\fixtures\NewsFixture;

class NewsTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function _fixtures() {
        return [
            'news' => [
                'class' => NewsFixture::className(),
                'dataFile' => codecept_data_dir() . 'models/news.php'
            ]
        ];
    }

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testFixturesData()
    {
        $data = $this->tester->grabFixture('news', 'news1');
        $this->assertEquals(1, $data->id);
    }

    public function testGetNewsPostUrl()
    {
        $news = $this->tester->grabFixture('news', 'news2');
        expect($news->getPostUrl())->equals('/some-test-news-2');
    }

    public function testGetPublicUrl()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        expect($news->url)->equals('/some-test-news-3');
    }

    public function testGetNewsImagePath()
    {
        $news = $this->tester->grabFixture('news', 'news3');
        expect($news->getImage())->equals('/Test-news3.jpg');
    }
}