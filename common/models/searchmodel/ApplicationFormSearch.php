<?php

namespace common\models\searchmodel;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ApplicationForm;

/**
 * ApplicationFormSearch represents the model behind the search form of `common\models\ApplicationForm`.
 */
class ApplicationFormSearch extends ApplicationForm
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'can_be_multiple'], 'integer'],
            [['form_name'], 'safe'],
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
        $query = ApplicationForm::find();

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
            'can_be_multiple' => $this->can_be_multiple,
        ]);

        $query->andFilterWhere(['like', 'form_name', $this->form_name]);

        return $dataProvider;
    }
}
