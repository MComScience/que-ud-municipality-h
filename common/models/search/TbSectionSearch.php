<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbSection;

/**
 * TbSectionSearch represents the model behind the search form about `common\models\user\TbSection`.
 */
class TbSectionSearch extends TbSection
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['section_id', 'section_des', 'department_id', 'section_grp_code', 'section_old_id', 'section_nhis'], 'safe'],
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
        $query = TbSection::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'section_id', $this->section_id])
            ->andFilterWhere(['like', 'section_des', $this->section_des])
            ->andFilterWhere(['like', 'department_id', $this->department_id])
            ->andFilterWhere(['like', 'section_grp_code', $this->section_grp_code])
            ->andFilterWhere(['like', 'section_old_id', $this->section_old_id])
            ->andFilterWhere(['like', 'section_nhis', $this->section_nhis]);

        return $dataProvider;
    }
}
