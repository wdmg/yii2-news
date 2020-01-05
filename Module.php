<?php

namespace wdmg\news;

/**
 * Yii2 News
 *
 * @category        Module
 * @version         1.0.5
 * @author          Alexsander Vyshnyvetskyy <alex.vyshnyvetskyy@gmail.com>
 * @link            https://github.com/wdmg/yii2-news
 * @copyright       Copyright (c) 2019 W.D.M.Group, Ukraine
 * @license         https://opensource.org/licenses/MIT Massachusetts Institute of Technology (MIT) License
 *
 */

use Yii;
use wdmg\base\BaseModule;
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
    private $version = "1.0.5";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 4;

    /**
     * @var string or array, the default routes to rendered news (use "/" - for root)
     */
    public $newsRoute = "/news";

    /**
     * @var string, the default layout to render news
     */
    public $newsLayout = "@app/views/layouts/main";

    /**
     * @var string, the default path to save news thumbnails in @webroot
     */
    public $newsImagePath = "/uploads/news";

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

        // Process and normalize route for pages in frontend
        $this->newsRoute = self::normalizeRoute($this->newsRoute);

        // Normalize path to image folder
        $this->newsImagePath = \yii\helpers\FileHelper::normalizePath($this->newsImagePath);
    }

    /**
     * {@inheritdoc}
     */
    public function dashboardNavItems($createLink = false)
    {
        $items = [
            'label' => $this->name,
            'url' => [$this->routePrefix . '/'. $this->id],
            'icon' => 'fa-newspaper-o',
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
        $newsRoute = $this->newsRoute;
        if (empty($newsRoute) || $newsRoute == "/") {
            $app->getUrlManager()->addRules([
                [
                    'pattern' => '/<alias:[\w-]+>',
                    'route' => 'admin/news/default/view',
                    'suffix' => ''
                ],
                '/<alias:[\w-]+>' => 'admin/news/default/view',
            ], true);
        } else {
            $app->getUrlManager()->addRules([
                [
                    'pattern' => $newsRoute,
                    'route' => 'admin/news/default/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => $newsRoute . '/<alias:[\w-]+>',
                    'route' => 'admin/news/default/view',
                    'suffix' => ''
                ],
                $newsRoute => 'admin/news/default/index',
                $newsRoute . '/<alias:[\w-]+>' => 'admin/news/default/view',
            ], true);
        }
    }


    /**
     * {@inheritdoc}
     */
    public function install()
    {
        parent::install();
        $path = Yii::getAlias('@webroot') . $this->newsImagePath;
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
        $path = Yii::getAlias('@webroot') . $this->newsImagePath;
        if (\yii\helpers\FileHelper::removeDirectory($path))
            return true;
        else
            return false;
    }
}