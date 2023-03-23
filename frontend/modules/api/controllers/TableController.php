<?php

namespace frontend\modules\api\controllers;

use Yii;
use yii\rest\Controller;

/**
 * Default controller for the `api` module
 */
class TableController extends Controller
{
    public function actionTruncate($table_name)
    {
        return Yii::$app->db->truncateTable($table_name);
    }

    public function actionAnyTable($table_name, $query = [])
    {
        $table = (new \yii\db\Query())
            ->select('*')
            ->from($table_name);
        if ($query) {
            $table->andWhere($query);
        }
        return $table->all();
    }

    public function actionGetTables()
    {
        $connection = Yii::$app->db;//get connection
        $dbSchema = $connection->schema;
        return $dbSchema->getTableNames();
    }
}
