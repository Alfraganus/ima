<?php

namespace common\models\searchmodel;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ApplicationWizard;

/**
 * ApplicationWizardSearch represents the model behind the search form of `common\models\ApplicationWizard`.
 */
class ApplicationWizardSearch extends ApplicationWizard
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'application_id'], 'integer'],
            [['wizard_name', 'wizard_icon'], 'safe'],
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
        $query = ApplicationWizard::find();

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
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'wizard_name', $this->wizard_name])
            ->andFilterWhere(['like', 'wizard_icon', $this->wizard_icon]);

        return $dataProvider;
    }
}
