<?php
/**
 * @author: jiangyi
 * @date: 下午3:02 2018/7/20
 */

namespace backend\modules\admin\controllers;

use backend\controllers\ApiController;
use backend\modules\admin\models\Menu;
use backend\modules\admin\models\searches\MenuSearch;
use mdm\admin\components\Helper;
use mdm\admin\components\MenuHelper;

use Yii;
use yii\web\NotFoundHttpException;
class MenuController extends ApiController
{
    /**
     * @var string
     */
    public $modelClass = 'backend\modules\admin\models\Route';

    protected function verbs()
    {
        return [
            'index' => ['GET', 'HEAD'],
            'refresh' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'delete' => ['delete'],
            'assigned-menu' => ['GET'],
        ];
    }

    public function prepareDataProvider()
    {
        $searchModel = new MenuSearch;
        return $searchModel->search(Yii::$app->request->getQueryParams());
    }

    public function actionCreate()
    {
        $model = new Menu;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->formatResponse();
        }
        $errors = $model->getFirstErrors();
        return $this->formatResponse(false, 1005, array_shift($errors));
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->menuParent) {
            $model->parent_name = $model->menuParent->name;
        }
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Helper::invalidate();
            return $this->formatResponse();
        }
        $errors = $model->getFirstErrors();
        return $this->formatResponse(false, 1005, array_shift($errors));
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Helper::invalidate();

        return $this->formatResponse();
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

   
    public function actionAssignedMenu()
    {
       
       return MenuHelper::getAssignedMenu(Yii::$app->user->getId());

    }

    protected function findModel($id)
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

     public function actionUserInfo()
    {
       $info = ["data"=>['roles' =>  ["admin"],
                        'code' => true,
                        'status' => 200
                        ]];
      
        return $info;
    }

     public function actionLogout()
    {
        Yii::$app->user->logout();

        return true;
    }
}
