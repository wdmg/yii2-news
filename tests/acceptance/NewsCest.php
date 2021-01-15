<?php

namespace wdmg\news\tests\acceptance;

use wdmg\news\tests\fixtures\ModulesFixture;
use wdmg\news\tests\fixtures\NewsFixture;
use wdmg\news\tests\fixtures\LangFixture;

class NewsCest
{
    public function _fixtures() {

        $fixtures['modules'] = [
            'class' => ModulesFixture::className(),
            'dataFile' => codecept_data_dir() . 'models/modules.php'
        ];

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

    public function _before(\AcceptanceTester $I)
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

    public function _after(\AcceptanceTester $I)
    {
    }

    public function tryToSeeIndexPage(\AcceptanceTester $I)
    {
        $I->wantTo('ensure that frontpage works');
        $I->amOnPage('/');
        $I->see('Congratulations!');
        $I->see('Yii-powered');
    }

    public function tryToSeeNewsPage(\AcceptanceTester $I)
    {
        $I->wantTo('ensure that test news avialible');
        $I->amOnPageRoute(['/news/some-test-news-1']);
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->see('Some test news headline #1');
        $I->see('Lorem ipsum dolor sit amet, consectetuer adipiscing elit');
    }
}
