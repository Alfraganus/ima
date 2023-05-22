<?php
namespace expert\modules\v1\services;

use common\models\ApplicationFormMedia;
use common\models\UserApplications;
use expert\models\forms\ExpertForm10;
use expert\models\forms\ExpertForm40;
use expert\models\forms\ExpertFormList;
use yii\data\Pagination;
use yii\db\Query;

class AdvancedSearch
{
    public function search2(array $columns,array $conditions=null)
    {
        $query = new Query();
        $models = [ new ExpertForm10(), new ExpertForm40(), new ApplicationFormMedia]; // add other models if necessary
        $firstModel = true;
        $select = [];
        $from = '';
        foreach ($models as $model) {

            $modelColumns = [];
            foreach ($columns as $column) {
                if ($model->hasAttribute($column) && !in_array("{$model->tableName()}.{$column}", $modelColumns)) {
                    $modelColumns[] = "{$model->tableName()}.{$column}";
                }

            }
            if (!empty($modelColumns)) {
                if ($firstModel) {
                    $from = $model->tableName();
                    $firstModel = false;
                } else {
                    $idColumn = $model instanceof ApplicationFormMedia ? 'application_id' : 'user_application_id';
                    $query->join('JOIN', $model->tableName(), "{$model->tableName()}.{$idColumn} = {$models[0]->tableName()}.user_application_id");
                }
                $select = array_merge($select, $modelColumns);
            }
        }

        if (!empty($select) && !empty($from)) {
            $query->select($select)->from($from);

            if($conditions) {
                foreach ($conditions as $condition) {
                    $query->andWhere([
                        $condition['operator'],
                        $condition['column'],
                        $condition['value']
                    ]);

                }
            }
            $countQuery = clone $query;
            $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 50]);
            $query->offset($pages->offset);
            $query->limit($pages->limit);


            $data = $query->all();
        } else {
            // handle the case when no columns were found in any model
        }
        return $data;
    }

    public function search(array $columnList=null)
    {

    }

    private static function columnList()
    {
        $columnList = [
            11 => 'column_11',
            21 => 'column_21',
        ];
    }

    private function getModel($columnName)
    {
        $expertForms = ExpertFormList::find()->all();

        foreach ($expertForms as $form) {
            $formClass = new $form->form_class;
            if($formClass->hasAttribute($columnName)) {
                return true;
            }
        }
    }

}
