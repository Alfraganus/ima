<?php

use yii\db\Migration;

/**
 * Class m230513_162953_create_tables_for_application_status
 */
class m230513_162953_create_tables_for_application_status extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%application_status}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(500),
            'description' => $this->string(255)->null(),
        ]);
        $this->addColumn('user_applications', 'status_id', $this->integer()
            ->after('is_finished')
            ->comment('applicationni hozirgi statusi')
        );

        $this->addForeignKey(
            'fk-user_applications-status_id',
            'user_applications',
            'status_id',
            'application_status',
            'id',
            'CASCADE'
        );

        $this->createTable('{{%application_status_management}}', [
            'id' => $this->primaryKey(),
            'user_application_id' => $this->integer(),
            'status_id' => $this->integer(255)->null(),
            'description' => $this->string(500)->null(),
            'start'=>$this->dateTime()->defaultExpression('NOW()')->null(),
            'finish'=>$this->dateTime()->null()
        ]);

        $this->addForeignKey(
            'fk-application_status_management-user_application_id',
            'application_status_management',
            'user_application_id',
            'user_applications',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-application_status_management-status_id',
            'application_status_management',
            'status_id',
            'application_status',
            'id',
            'CASCADE'
        );

        $statuses = [
            'В ожидании формальной экспертизы',
            'На формальной экспертизе',
            'На стадии уплаты пошлины за экспертизу',
            'В ожидании экспертизы',
            'Формальная экспертиза просрочена',
            'Восстановленные',
            'Отозванные',
            'На экспертизе',
            'Экспертиза завершена',
            'Экспертиза просрочена',
        ];

        foreach ($statuses as $status) {
            Yii::$app->db->createCommand()->insert('application_status', [
                'name' => $status,
            ])->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-user_applications-status_id',
            'user_applications'
        );
        $this->dropForeignKey(
            'fk-application_status_management-status_id',
            'application_status_management'
        );
        $this->dropForeignKey(
            'fk-application_status_management-user_application_id',
            'application_status_management'
        );
        $this->dropColumn('user_applications', 'status_id');
        $this->dropTable('{{%application_status}}');
        $this->dropTable('{{%application_status_management}}');
    }

}
