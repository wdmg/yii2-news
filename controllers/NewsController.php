<?php

namespace wdmg\news\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use wdmg\news\models\News;
use wdmg\news\models\NewsSearch;

/**
 * NewsController implements the CRUD actions for News model.
 */
class NewsController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get'],
                    'view' => ['get'],
                    'delete' => ['post'],
                    'create' => ['get', 'post'],
                    'update' => ['get', 'post'],
                    'export' => ['get'],
                    'import' => ['post'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['admin'],
                        'allow' => true
                    ],
                ],
            ],
        ];

        // If auth manager not configured use default access control
        if(!Yii::$app->authManager) {
            $behaviors['access'] = [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'roles' => ['@'],
                        'allow' => true
                    ],
                ]
            ];
        }

        return $behaviors;
    }

    /**
     * Lists of all News models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'module' => $this->module
        ]);
    }


    /**
     * Finds the News model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return news model item
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = News::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app/modules/news', 'The requested news does not exist.'));
    }
}
