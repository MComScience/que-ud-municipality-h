<?php

namespace common\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\TbDepartment;

/**
 * TbDepartmentSearch represents the model behind the search form about `common\models\user\TbDepartment`.
 */
class TbDepartmentSearch extends TbDepartment
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['department_id', 'department_des', 'department_type_sect_grp_code', 'department_old_id_department'], 'safe'],
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
        $query = TbDepartment::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'department_id', $this->department_id])
            ->andFilterWhere(['like', 'department_des', $this->department_des])
            ->andFilterWhere(['like', 'department_type_sect_grp_code', $this->department_type_sect_grp_code])
            ->andFilterWhere(['like', 'department_old_id_department', $this->department_old_id_department]);

        return $dataProvider;
    }
}
