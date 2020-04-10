[![Yii2](https://img.shields.io/badge/required-Yii2_v2.0.33-blue.svg)](https://packagist.org/packages/yiisoft/yii2)
[![Github all releases](https://img.shields.io/github/downloads/wdmg/yii2-news/total.svg)](https://GitHub.com/wdmg/yii2-news/releases/)
![Progress](https://img.shields.io/badge/progress-ready_to_use-green.svg)
[![GitHub license](https://img.shields.io/github/license/wdmg/yii2-news.svg)](https://github.com/wdmg/yii2-news/blob/master/LICENSE)
![GitHub release](https://img.shields.io/github/release/wdmg/yii2-news/all.svg)

# Yii2 News
News module for Yii2

# Requirements 
* PHP 5.6 or higher
* Yii2 v.2.0.33 and newest
* [Yii2 Base](https://github.com/wdmg/yii2-base) module (required)
* [Yii2 Editor](https://github.com/wdmg/yii2-editor) module (required)
* [Yii2 SelectInput](https://github.com/wdmg/yii2-selectinput) widget

# Installation
To install the module, run the following command in the console:

`$ composer require "wdmg/yii2-news"`

After configure db connection, run the following command in the console:

`$ php yii news/init`

And select the operation you want to perform:
  1) Apply all module migrations
  2) Revert all module migrations

# Migrations
In any case, you can execute the migration and create the initial data, run the following command in the console:

`$ php yii migrate --migrationPath=@vendor/wdmg/yii2-news/migrations`

# Configure
To add a module to the project, add the following data in your configuration file:

    'modules' => [
        ...
        'news' => [
            'class' => 'wdmg\news\Module',
            'routePrefix' => 'admin',
            'baseRoute'  => '/news', // default routes to rendered news in @frontend (use "/" - for root)
            'baseLayout' => '@app/views/layouts/main', // default layout to render news in @frontend
            'imagePath' => '/uploads/news' // the default path to save news thumbnails in @webroot
        ],
        ...
    ],


# Routing
Use the `Module::dashboardNavItems()` method of the module to generate a navigation items list for NavBar, like this:

    <?php
        echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
            'label' => 'Modules',
            'items' => [
                Yii::$app->getModule('news')->dashboardNavItems(),
                ...
            ]
        ]);
    ?>

# Status and version [ready to use]
* v.1.0.10 - Log activity
* v.1.0.9 - Added pagination, up to date dependencies
* v.1.0.8 - Refactoring. Migrations bugfix
* v.1.0.7 - Image save bugfix
* v.1.0.6 - Added support for RSS-feed, Yandex.Turbo and Google AMP modules
* v.1.0.5 - Added support for Sitemap module