<?php

use yii\db\Migration;
use yii\db\Command;

/**
 * Handles the creation of table `{{%world_countries}}`.
 */
class m230425_105944_create_world_countries_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%world_countries}}', [
            'id' => $this->primaryKey(),
            'country_code' => $this->string(10),
            'country_name' => $this->string(150),
            'region' => $this->string(150),
        ]);

        foreach ($this->getCountries() as $key => $value) {
            Yii::$app->db->createCommand()->insert('world_countries', [
                'country_code' => strtoupper($key),
                'country_name' => $value['name'],
                'region' => $value['region'],
            ])->execute();
        }
    }

    private function getCountries()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://countryapi.io/api/all?apikey=81Btqr26U3jxuz7zqHVsaGG6LiqWINAl4CtLTrgu',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Authorization: Bearer oyM5n5_6m5Ln_a1xvHcLJTA9F-YL9lRt'
            ),
        ));
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response, true);
    }


    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%world_countries}}');
    }
}
