<?php

namespace wdmg\news;

/**
 * Yii2 News
 *
 * @category        Module
 * @version         1.1.0
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-news
 * @copyright       Copyright (c) 2019 - 2020 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * News module definition class
 */
class Module extends BaseModule
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'wdmg\news\controllers';

    /**
     * {@inheritdoc}
     */
    public $defaultRoute = "news/index";

    /**
     * @var string, the name of module
     */
    public $name = "News";

    /**
     * @var string, the description of module
     */
    public $description = "News manager";

    /**
     * @var string the module version
     */
    private $version = "1.1.0";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 4;

    /**
     * @var string the default routes to rendered news in @frontend (use "/" - for root)
     */
    public $baseRoute = "/news";

    /**
     * @var string, the default layout to render news in @frontend
     */
    public $baseLayout = "@app/views/layouts/main";

    /**
     * @var string, the default path to save news thumbnails in @webroot
     */
    public $imagePath = "/uploads/news";

    /**
     * @var array, the list of support locales for multi-language versions of page.
     * @note This variable will be override if you use the `wdmg\yii2-translations` module.
     */
    public $supportLocales = ['ru-RU', 'uk-UA', 'en-US'];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Set version of current module
        $this->setVersion($this->version);

        // Set priority of current module
        $this->setPriority($this->priority);

        if (isset(Yii::$app->params["news.baseRoute"]))
            $this->baseRoute = Yii::$app->params["news.baseRoute"];

        if (isset(Yii::$app->params["news.supportLocales"]))
            $this->supportLocales = Yii::$app->params["news.supportLocales"];

        if (!isset($this->baseRoute))
            throw new InvalidConfigException("Required module property `baseRoute` isn't set.");

        // Process and normalize route for news in frontend
        $this->baseRoute = self::normalizeRoute($this->baseRoute);

        // Normalize path to image folder
        $this->imagePath = \yii\helpers\FileHelper::normalizePath($this->imagePath);
    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($createLink = false)
    {
        $items = [
            'label' => $this->name,
            'url' => [$this->routePrefix . '/'. $this->id],
            'icon' => 'fa fa-fw fa-newspaper',
            'active' => in_array(\Yii::$app->controller->module->id, [$this->id])
        ];
        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function bootstrap($app)
    {
        parent::bootstrap($app);

        // Add routes to news in frontend
        $app->getUrlManager()->addRules([
            '/<lang:\w+>' . $this->baseRoute . '/<alias:[\w-]+>' => 'admin/news/default/view',
            $this->baseRoute . '/<alias:[\w-]+>' => 'admin/news/default/view',
            '/<lang:\w+>' . $this->baseRoute => 'admin/news/default/index',
            $this->baseRoute => 'admin/news/default/index',
        ], true);
    }


    /**
     * {@inheritdoc}
     */
    public function install()
    {
        parent::install();
        $path = Yii::getAlias('@webroot') . $this->imagePath;
        if (\yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true))
            return true;
        else
            return false;
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall()
    {
        parent::uninstall();
        $path = Yii::getAlias('@webroot') . $this->imagePath;
        if (\yii\helpers\FileHelper::removeDirectory($path))
            return true;
        else
            return false;
    }
}