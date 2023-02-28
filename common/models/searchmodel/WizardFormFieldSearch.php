<?php

namespace common\models\searchmodel;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\WizardFormField;

/**
 * WizardFormFieldSearch represents the model behind the search form of `common\models\WizardFormField`.
 */
class WizardFormFieldSearch extends WizardFormField
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'wizard_id', 'form_id', 'order_id'], 'integer'],
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
        $query = WizardFormField::find();

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
            'wizard_id' => $this->wizard_id,
            'form_id' => $this->form_id,
            'order_id' => $this->order_id,
        ]);

        return $dataProvider;
    }
}
