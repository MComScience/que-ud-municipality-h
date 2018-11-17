<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbDistrict;

/**
 * TbDistrictSearch represents the model behind the search form about `common\models\user\TbDistrict`.
 */
class TbDistrictSearch extends TbDistrict
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['district_id', 'amphur_id', 'province_id', 'geo_id'], 'integer'],
            [['district_code', 'district_name'], 'safe'],
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
        $query = TbDistrict::find();

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
            'district_id' => $this->district_id,
            'amphur_id' => $this->amphur_id,
            'province_id' => $this->province_id,
            'geo_id' => $this->geo_id,
        ]);

        $query->andFilterWhere(['like', 'district_code', $this->district_code])
            ->andFilterWhere(['like', 'district_name', $this->district_name]);

        return $dataProvider;
    }
}
