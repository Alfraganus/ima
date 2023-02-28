<?php

namespace common\models\searchmodel;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ApplicationFormField;

/**
 * ApplicationFormFieldSearch represents the model behind the search form of `common\models\ApplicationFormField`.
 */
class ApplicationFormFieldSearch extends ApplicationFormField
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'is_compulsory'], 'integer'],
            [['field_name', 'data_type'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = ApplicationFormField::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'is_compulsory' => $this->is_compulsory,
        ]);

        $query->andFilterWhere(['like', 'field_name', $this->field_name])
            ->andFilterWhere(['like', 'data_type', $this->data_type]);

        return $dataProvider;
    }
}
