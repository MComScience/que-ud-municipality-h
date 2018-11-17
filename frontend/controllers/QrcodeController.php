<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use frontend\modules\app\traits\ModelTrait;
use yii\web\Response;
use yii\helpers\Html;

class QrcodeController extends Controller
{
    use ModelTrait;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?','@'],
                    ],
                ],
            ],
        ];
    }

    public function actionMobileView($id)
    {
        $this->layout = '@app/views/layouts/mobile-view';
        $modelQue = $this->findModelQue($id);
        $sql = 'SELECT
                Count(tb_que.que_ids) as count
                FROM
                tb_que
                WHERE
                tb_que.service_group_id = :service_group_id AND
                tb_que.que_ids < :que_ids AND que_status_id = 1';
        $params = [':service_group_id' => $modelQue['service_group_id'], ':que_ids' => $id];
        $count = Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();
        return $this->renderAjax('mobile-view',[
            'modelQue' => $modelQue,
            'count' => $count,
        ]);
    }

    public function actionViewDrug($id)
    {
        $request = Yii::$app->request;
        $modelQue = $this->findModelQue($id);
        if ($request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $db = Yii::$app->db_ud;
            $rxdate = Yii::$app->formatter->asDate('now','php:d/m/') . (Yii::$app->formatter->asDate('now','php:Y')+543);
            $query = $db->createCommand('SELECT * FROM dbo.Q_Detail WHERE dbo.Q_Detail.hn = '.$modelQue['que_hn'].' AND dbo.Q_Detail.RX_DATE = \''.$rxdate.'\'')->queryAll();
            return [
                'title' => 'รายละเอียดยา',
                'content' => $query ? $this->renderAjax('view-drug',[
                    'query' => $query,
                    'modelQue' => $modelQue,
                ]) : '<div class="alert alert-danger" role="alert">ไม่พบข้อมูล</div>',
                'footer' => Html::button('Close',['class' => 'btn btn-default','data-dismiss' => 'modal'])
            ];
        }
    }

    public function actionGetCount($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $modelQue = $this->findModelQue($id);
        $sql = 'SELECT
                Count(tb_que.que_ids) as count
                FROM
                tb_que
                WHERE
                tb_que.service_group_id = :service_group_id AND
                tb_que.created_at < :created_at AND que_status_id = 1';
        $params = [':service_group_id' => $modelQue['service_group_id'], ':created_at' => $modelQue['created_at']];
        $count = Yii::$app->db->createCommand($sql)
            ->bindValues($params)
            ->queryScalar();
        return $count;
    }
}