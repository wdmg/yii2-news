<?php

namespace wdmg\news\models;

use Yii;
use wdmg\base\models\ActiveRecordML;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property string $image_src
 * @property string $excerpt
 * @property string $content
 * @property string $title
 * @property string $description
 * @property string $keywords
 * @property boolean $in_sitemap
 * @property boolean $in_rss
 * @property boolean $in_turbo
 * @property boolean $in_amp
 * @property boolean $status
 * @property array $source
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class News extends ActiveRecordML
{
    const STATUS_DRAFT = 0; // News post has draft
    const STATUS_PUBLISHED = 1; // News post has been published

    public $uniqueAttributes = ['alias'];

    public $route;
    public $file;

    /**
     * @var object
     */
    private $_module;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!($this->_module = Yii::$app->getModule('admin/news', false)))
            $this->_module = Yii::$app->getModule('news', false);

        if (isset(Yii::$app->params["news.baseRoute"]))
            $this->baseRoute = Yii::$app->params["news.baseRoute"];
        elseif (isset($this->_module->baseRoute))
            $this->baseRoute = $this->_module->baseRoute;

    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return ArrayHelper::merge([
            [['name', 'alias', 'content'], 'required'],
            [['name', 'alias'], 'string', 'min' => 3, 'max' => 128],
            [['excerpt', 'title', 'description', 'keywords', 'image'], 'string', 'max' => 255],
            [['file'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 1, 'extensions' => 'png, jpg'],
        ], parent::rules());
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge([
            'id' => Yii::t('app/modules/news', 'ID'),
            'source_id' => Yii::t('app/modules/news', 'Source ID'),
            'name' => Yii::t('app/modules/news', 'Name'),
            'alias' => Yii::t('app/modules/news', 'Alias'),
            'image' => Yii::t('app/modules/news', 'Image'),
            'file' => Yii::t('app/modules/news', 'Image file'),
            'excerpt' => Yii::t('app/modules/news', 'Excerpt'),
            'content' => Yii::t('app/modules/news', 'News text'),
            'title' => Yii::t('app/modules/news', 'Title'),
            'description' => Yii::t('app/modules/news', 'Description'),
            'keywords' => Yii::t('app/modules/news', 'Keywords'),
            'in_sitemap' => Yii::t('app/modules/news', 'In sitemap?'),
            'in_rss' => Yii::t('app/modules/news', 'In RSS-feed?'),
            'in_turbo' => Yii::t('app/modules/news', 'Yandex turbo-pages?'),
            'in_amp' => Yii::t('app/modules/news', 'Google AMP?'),
            'locale' => Yii::t('app/modules/news', 'Locale'),
            'status' => Yii::t('app/modules/news', 'Status'),
            'created_at' => Yii::t('app/modules/news', 'Created at'),
            'created_by' => Yii::t('app/modules/news', 'Created by'),
            'updated_at' => Yii::t('app/modules/news', 'Updated at'),
            'updated_by' => Yii::t('app/modules/news', 'Updated by'),
        ], parent::attributeLabels());
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        if (is_null($this->url))
            $this->url = $this->getUrl();

    }

    /**
     * Return the statuses list of News items
     *
     * @return array
     */
    public function getStatusesList($allStatuses = false)
    {
        if($allStatuses)
            return [
                '*' => Yii::t('app/modules/news', 'All statuses'),
                self::STATUS_DRAFT => Yii::t('app/modules/news', 'Draft'),
                self::STATUS_PUBLISHED => Yii::t('app/modules/news', 'Published'),
            ];
        else
            return [
                self::STATUS_DRAFT => Yii::t('app/modules/news', 'Draft'),
                self::STATUS_PUBLISHED => Yii::t('app/modules/news', 'Published'),
            ];
    }

    /**
     * Build and return image path for image save
     *
     * @return string
     */
    public function getImagePath($absoluteUrl = false)
    {

        if (isset(Yii::$app->params["news.imagePath"])) {
            $imagePath = Yii::$app->params["news.imagePath"];
        } else {

            if (!$module = Yii::$app->getModule('admin/news'))
                $module = Yii::$app->getModule('news');

            $imagePath = $module->imagePath;
        }

        if ($absoluteUrl)
            return \yii\helpers\Url::to(str_replace('\\', '/', $imagePath), true);
        else
            return $imagePath;

    }

    /**
     * Processed image upload and return filename
     *
     * @param null $image
     * @return bool|string
     * @throws \yii\base\Exception
     */
    public function upload($image = null)
    {
        if (!$image)
            return false;

        $path = Yii::getAlias('@webroot') . $this->getImagePath();
        if ($image) {
            // Create the folder if not exist
            if (\yii\helpers\FileHelper::createDirectory($path, $mode = 0775, $recursive = true)) {
                $fileName = $image->baseName . '.' . $image->extension;
                if ($image->saveAs($path . '/' . $fileName))
                    return $fileName;
            }
        }
        return false;
    }


    /**
     * @param bool $withScheme
     * @param bool $realUrl
     * @return string|null
     */
    public function getPostUrl($withScheme = true, $realUrl = false)
    {
        return parent::getModelUrl($withScheme, $realUrl);
    }

}
