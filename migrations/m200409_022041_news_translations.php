<?php

use yii\db\Migration;

/**
 * Class m200409_022041_news_translations
 */
class m200409_022041_news_translations extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $defaultLocale = null;
        if (isset(Yii::$app->sourceLanguage))
            $defaultLocale = Yii::$app->sourceLanguage;

        if (is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('source_id'))) {
            $this->addColumn('{{%news}}', 'source_id', $this->bigInteger()->null()->after('id'));

            // Setup foreign key to source id
            $this->createIndex('{{%idx-news-source}}', '{{%news}}', ['source_id']);
            $this->addForeignKey(
                'fk_news_to_source',
                '{{%news}}',
                'source_id',
                '{{%news}}',
                'id',
                'NO ACTION',
                'CASCADE'
            );

        }
        if (is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('locale'))) {

            $this->addColumn('{{%news}}', 'locale', $this->string(10)->defaultValue($defaultLocale)->after('status'));
            $this->createIndex('{{%idx-news-locale}}', '{{%news}}', ['locale']);

            // If module `Translations` exist setup foreign key `locale` to `trans_langs.locale`
            if (class_exists('\wdmg\translations\models\Languages')) {
                $langsTable = \wdmg\translations\models\Languages::tableName();
                $this->addForeignKey(
                    'fk_news_to_langs',
                    '{{%news}}',
                    'locale',
                    $langsTable,
                    'locale',
                    'NO ACTION',
                    'CASCADE'
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (!is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('source_id'))) {
            $this->dropIndex('{{%idx-news-source}}', '{{%news}}');
            $this->dropColumn('{{%news}}', 'source_id');
            $this->dropForeignKey(
                'fk_news_to_source',
                '{{%news}}'
            );
        }
        if (!is_null($this->getDb()->getSchema()->getTableSchema('{{%news}}')->getColumn('locale'))) {
            $this->dropIndex('{{%idx-news-locale}}', '{{%news}}');
            $this->dropColumn('{{%news}}', 'locale');

            if (class_exists('\wdmg\translations\models\Languages')) {
                $langsTable = \wdmg\translations\models\Languages::tableName();
                if (!(Yii::$app->db->getTableSchema($langsTable, true) === null)) {
                    $this->dropForeignKey(
                        'fk_news_to_langs',
                        '{{%news}}'
                    );
                }
            }
        }
    }
}
