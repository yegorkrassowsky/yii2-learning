<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "lookup".
 *
 * @property integer $id
 * @property string $name
 * @property integer $code
 * @property string $type
 * @property integer $position
 */
class Lookup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lookup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'position'], 'integer'],
            [['name', 'type'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'code' => 'Code',
            'type' => 'Type',
            'position' => 'Position',
        ];
    }
    
    private static $_items = array();

/*
    public static function item($type,$code)
    {
	    if(!isset(self::$_items[$type])) self::loadItems($type);
	    return isset(self::$_items[$type][$code]) ? self::$_items[$type][$code] : false;
    }
*/
    
    public static function items($type)
    {
	    if(!isset(self::$_items[$type])) self::loadItems($type);
	    return self::$_items[$type];
    }
        
    private static function loadItems($type)
    {
	    self::$_items[$type]=array();
	    $models=self::find()
	    	->where(['type' => $type])
	    	->orderBy('position')
	    	->all();
	    
	    foreach($models as $model)
	    {
		    self::$_items[$type][$model->code]=$model->name;
	    }
    }
}
