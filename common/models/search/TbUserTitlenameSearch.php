<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbUserTitlename;

/**
 * TbUserTitlenameSearch represents the model behind the search form about `common\models\user\TbUserTitlename`.
 */
class TbUserTitlenameSearch extends TbUserTitlename
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_titlename_id', 'user_titlename'], 'safe'],
            [['user_sex_id'], 'integer'],
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
        $query = TbUserTitlename::find();

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
            'user_sex_id' => $this->user_sex_id,
        ]);

        $query->andFilterWhere(['like', 'user_titlename_id', $this->user_titlename_id])
            ->andFilterWhere(['like', 'user_titlename', $this->user_titlename]);

        return $dataProvider;
    }
}
