<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 21:28
 */
namespace frontend\modules\app\traits;

use frontend\modules\app\models\TbServiceGroup;
use frontend\modules\app\models\TbCounterService;
use frontend\modules\app\models\TbCounterServiceType;
use frontend\modules\app\models\TbSoundStation;
use frontend\modules\app\models\TbServiceProfile;
use frontend\modules\app\models\TbTicket;
use frontend\modules\app\models\TbDisplay;
use frontend\modules\app\models\TbQue;
use frontend\modules\app\models\TbService;
use frontend\modules\app\models\TbCaller;
use frontend\modules\app\models\TbDevice;
use yii\web\NotFoundHttpException;

trait ModelTrait
{
    protected function findModelServiceGroup($id)
    {
        if (($model = TbServiceGroup::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelCounterService($id)
    {
        if (($model = TbCounterService::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelCounterServiceType($id)
    {
        if (($model = TbCounterServiceType::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelSoundStation($id)
    {
        if (($model = TbSoundStation::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelServiceProfile($id)
    {
        if (($model = TbServiceProfile::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelTicket($id)
    {
        if (($model = TbTicket::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelDisplay($id)
    {
        if (($model = TbDisplay::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelQue($id)
    {
        if (($model = TbQue::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelService($id)
    {
        if (($model = TbService::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    protected function findModelCaller($id)
    {
        if (($model = TbCaller::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }

    private function handleError()
    {
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModelDevice($id)
    {
        if (($model = TbDevice::findOne($id)) !== null) {
            return $model;
        } else {
            $this->handleError();
        }
    }
}