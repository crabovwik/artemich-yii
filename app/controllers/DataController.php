<?php

namespace app\controllers;

use app\models\ImageUpload;
use Yii;
use app\models\Data;
use app\models\DataSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

class DataController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new DataSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Data();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->request->isPost) {
            $uploadedFile = UploadedFile::getInstance($model, 'image');

            if ($uploadedFile) {
                $imageUpload = new ImageUpload($uploadedFile);

                list($savePath, $imageName) = $model->getImageSavePathData($imageUpload->file);

                $model->image = $imageName;
            }

            if ($model->save()) {
                if ($uploadedFile) {
                    $imageUpload->uploadImage($savePath . DIRECTORY_SEPARATOR . $imageName);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $oldImage = $model->image;

        if ($model->load(Yii::$app->request->post()) && $model->validate() && Yii::$app->request->isPost) {
            $uploadedFile = UploadedFile::getInstance($model, 'image');
            if ($uploadedFile) {
                $oldImagePath = $model->getImagesFolder() . DIRECTORY_SEPARATOR . $oldImage;
                if ($oldImage && file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }

                $imageUpload = new ImageUpload($uploadedFile);

                list($savePath, $imageName) = $model->getImageSavePathData($imageUpload->file);

                $model->image = $imageName;
            }

            if ($model->save()) {
                if ($uploadedFile) {
                    $imageUpload->uploadImage($savePath . DIRECTORY_SEPARATOR . $imageName);
                }

                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Data::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
