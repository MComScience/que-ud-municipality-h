<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbAmphur;

/**
 * TbAmphurSearch represents the model behind the search form about `common\models\user\TbAmphur`.
 */
class TbAmphurSearch extends TbAmphur
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['amphur_id', 'geo_id', 'province_id'], 'integer'],
            [['amphur_code', 'amphur_name'], 'safe'],
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
        $query = TbAmphur::find();

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
            'amphur_id' => $this->amphur_id,
            'geo_id' => $this->geo_id,
            'province_id' => $this->province_id,
        ]);

        $query->andFilterWhere(['like', 'amphur_code', $this->amphur_code])
            ->andFilterWhere(['like', 'amphur_name', $this->amphur_name]);

        return $dataProvider;
    }
}
