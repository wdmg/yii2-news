<?php

namespace wdmg\news;

/**
 * Yii2 News
 *
 * @category        Module
 * @version         1.0.1
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
    private $version = "1.0.1";

    /**
     * @var integer, priority of initialization
     */
    private $priority = 4;

    /**
     * @var string or array, the default routes to rendered news (use "/" - for root)
     */
    public $newsRoute = "/news";

    /**
     * @var string, the default layout to rendered news
     */
    public $newsLayout = "@app/views/layouts/main";

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
                    'pattern' => '/<news:[\w-]+>',
                    'route' => 'admin/news/default/view',
                    'suffix' => ''
                ],
                '/<news:[\w-]+>' => 'admin/news/default/view',
            ], true);
        } else {
            $app->getUrlManager()->addRules([
                [
                    'pattern' => $newsRoute,
                    'route' => 'admin/news/default/index',
                    'suffix' => ''
                ],
                [
                    'pattern' => $newsRoute . '/<news:[\w-]+>',
                    'route' => 'admin/news/default/view',
                    'suffix' => ''
                ],
                $newsRoute => 'admin/news/default/index',
                $newsRoute . '/<news:[\w-]+>' => 'admin/news/default/view',
            ], true);
        }
    }
}