<?php

namespace common\modules\translation\models;

use common\modules\translation\traits\ModuleTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\i18n\DbMessageSource;

/**
 * This is the model class for table "{{%i18n_source_message}}".
 *
 * @property integer       $id
 * @property string        $category
 * @property string        $message
 *
 * @property Translation[] $translations
 */
class Source extends ActiveRecord
{
    use ModuleTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%i18n_source_message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message'], 'required'],
            [['message'], 'string'],
            [['category'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => Yii::t('backend', 'ID'),
            'category' => Yii::t('backend', 'Category'),
            'message'  => Yii::t('backend', 'Message'),
        ];
    }

    /** @inheritdoc */
    public function __get($name)
    {
        if (in_array($name, array_keys($this->getLanguages()))) {
            return $this->getTranslation($name);
        }

        return parent::__get($name);
    }

    /**
     * @param $language
     *
     * @return Translation|null
     */
    public function getTranslation($language)
    {
        foreach ($this->translations as $translation) {
            if ($translation->language == $language) return $translation;
        }

        return null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTranslations()
    {
        return $this->hasMany(Translation::class, ['id' => 'id']);
    }

    public function afterSave ( $insert, $changedAttributes )
    {
        parent::afterSave($insert, $changedAttributes);
        $modelMsg = Translation::findOne(['id' => $this->id]);
        if ($modelMsg){
            $cache = Yii::$app->cache;
            $key = [
                DbMessageSource::className(),
                $this->category,
                $modelMsg['language'],
            ];
            $cache->delete($key);
        }
        return true;
    }
}
