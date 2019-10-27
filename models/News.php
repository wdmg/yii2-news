<?php

namespace wdmg\news\models;

use Yii;
use yii\db\Expression;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\base\InvalidArgumentException;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;

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
 * @property integer $status
 * @property array $source
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class News extends ActiveRecord
{
    public $route;
    const POST_STATUS_DRAFT = 0; // News post has draft
    const POST_STATUS_PUBLISHED = 1; // News post has been published

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
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'created_at',
                    ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
                ],
                'value' => new Expression('NOW()'),
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            'sluggable' =>  [
                'class' => SluggableBehavior::className(),
                'attribute' => ['name'],
                'slugAttribute' => 'alias',
                'ensureUnique' => true,
                'skipOnEmpty' => true,
                'immutable' => true,
                'value' => function ($event) {
                    return mb_substr($this->name, 0, 32);
                }
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        $rules = [
            [['name', 'alias', 'content'], 'required'],
            [['name', 'alias'], 'string', 'min' => 3, 'max' => 128],
            [['name', 'alias'], 'string', 'min' => 3, 'max' => 128],
            [['excerpt', 'title', 'description', 'keywords'], 'string', 'max' => 255],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
            [['status'], 'boolean'],
            ['alias', 'unique', 'message' => Yii::t('app/modules/pages', 'Param attribute must be unique.')],
            ['alias', 'match', 'pattern' => '/^[A-Za-z0-9\-\_]+$/', 'message' => Yii::t('app/modules/pages','It allowed only Latin alphabet, numbers and the «-», «_» characters.')],
            [['source', 'created_at', 'updated_at'], 'safe'],
        ];

        if(class_exists('\wdmg\users\models\Users') && isset(Yii::$app->modules['users'])) {
            $rules[] = [['created_by', 'updated_by'], 'required'];
        }

        return $rules;
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app/modules/news', 'ID'),
            'name' => Yii::t('app/modules/news', 'Name'),
            'alias' => Yii::t('app/modules/news', 'Alias'),
            'image' => Yii::t('app/modules/news', 'Image'),
            'excerpt' => Yii::t('app/modules/news', 'Excerpt'),
            'content' => Yii::t('app/modules/news', 'News text'),
            'title' => Yii::t('app/modules/news', 'Title'),
            'description' => Yii::t('app/modules/news', 'Description'),
            'keywords' => Yii::t('app/modules/news', 'Keywords'),
            'status' => Yii::t('app/modules/news', 'Status'),
            'source' => Yii::t('app/modules/news', 'Source'),
            'created_at' => Yii::t('app/modules/news', 'Created at'),
            'created_by' => Yii::t('app/modules/news', 'Created by'),
            'updated_at' => Yii::t('app/modules/news', 'Updated at'),
            'updated_by' => Yii::t('app/modules/news', 'Updated by'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(is_array($this->source))
            $this->source = serialize($this->source);

        return parent::beforeSave($insert);
    }

    /**
     * @return array
     */
    public function getStatusesList($allStatuses = false)
    {
        if($allStatuses)
            return [
                '*' => Yii::t('app/modules/news', 'All statuses'),
                self::POST_STATUS_DRAFT => Yii::t('app/modules/news', 'Draft'),
                self::POST_STATUS_PUBLISHED => Yii::t('app/modules/news', 'Published'),
            ];
        else
            return [
                self::POST_STATUS_DRAFT => Yii::t('app/modules/news', 'Draft'),
                self::POST_STATUS_PUBLISHED => Yii::t('app/modules/news', 'Published'),
            ];
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return Yii::$app->controller->module->newsRoute;
    }

    /**
     * @return string
     */
    public function getImagePath($absoluteUrl = false)
    {
        if ($absoluteUrl)
            return \yii\helpers\Url::to(str_replace('\\', '/', Yii::$app->controller->module->newsImagePath), true);
        else
            return Yii::$app->controller->module->newsImagePath;
    }

    /**
     *
     * @param $withScheme boolean, absolute or relative URL
     * @return string or null
     */
    public function getPostUrl($withScheme = true, $realUrl = false)
    {
        $this->route = $this->getRoute();
        if (isset($this->alias)) {
            if ($this->status == self::POST_STATUS_DRAFT && $realUrl)
                return \yii\helpers\Url::to(['default/view', 'alias' => $this->alias, 'draft' => 'true'], $withScheme);
            else
                return \yii\helpers\Url::to($this->route . '/' .$this->alias, $withScheme);

        } else {
            return null;
        }
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUser()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasOne(\wdmg\users\models\Users::className(), ['id' => 'created_by']);
        else
            return null;
    }

    /**
     * @return object of \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        if(class_exists('\wdmg\users\models\Users'))
            return $this->hasMany(\wdmg\users\models\Users::className(), ['id' => 'created_by']);
        else
            return null;
    }


    public function upload($image)
    {
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
}
