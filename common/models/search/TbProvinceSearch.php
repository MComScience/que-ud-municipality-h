<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbProvince;

/**
 * TbProvinceSearch represents the model behind the search form about `common\models\user\TbProvince`.
 */
class TbProvinceSearch extends TbProvince
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['province_id', 'geo_id'], 'integer'],
            [['province_code', 'province_name'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TbProvince::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'province_id' => $this->province_id,
            'geo_id' => $this->geo_id,
        ]);

        $query->andFilterWhere(['like', 'province_code', $this->province_code])
            ->andFilterWhere(['like', 'province_name', $this->province_name]);

        return $dataProvider;
    }
}
