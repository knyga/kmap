<?php

/**
 * Oleksandr Knyga <oleksandrknyga@gmail.com>
 */
class KMap
{
	/**
	 * Groups objects by same attribute
	 * @param  array  $models    
	 * @param  string $attribute name of the attribute
	 * @return array            grouped objects, key => array
	 */
	public static function group(array $models, $attribute)
	{
		$output=array();
		
		for($i=0; $i<count($models); $i++)
		{
			if($models[$i] instanceof CActiveRecord) {
				$output[$models[$i]->getAttribute($attribute)][]=$models[$i];
			} elseif(is_object($models[$i])) {
				$output[$models[$i]->$attribute][]=$models[$i];
			} elseif(is_array($models[$i])) {
				$output[$models[$i][$attribute]][]=$models[$i];
			} else {
				throw new Exception('Unsapported operands type');
			}
		}

		return $output;
	}

	/**
	 * Filter objects by given condition
	 * @param  array  $models    objects to filter
	 * @param  array  $condition condition
	 * @return array            filtered objects
	 */
	public static function filter(array $models, $condition = array()) {
		$arr = array_filter($models, function($model) use($condition) {

			foreach($condition as $key=>$value) {
				if(!self::has($model, $key)) {
					return false;
				}

				$mv = self::get($model, $key);

				if($mv != $value) {
					return false;
				}
			}

			return true;

		});

		return array_values($arr);
	}

	/**
	 * Maps array of objects to array of values from object attribute
	 * @param  array  $models    array of objects
	 * @param  string $attribute name of attribute
	 * @return array            array of values
	 */
	public static function gets(array $models, $attribute)
	{
		return array_map(function($model) use($attribute) {
			return self::get($model, $attribute);
		}, $models);
	}

	/**
	 * Gets value of object
	 * @param  array  $model     model to search value
	 * @param  string $attribute name of attribute
	 * @return misc            value from attribute
	 */
	public static function get($model, $attribute)
	{
		if($model instanceof CActiveRecord) {
			return $model->getAttribute($attribute);
		} elseif(is_object($model)) {
			return $model->$attribute;
		} elseif(is_array($model)) {
			return $model[$attribute];
		} else {
			throw new Exception('Unsapported operands type');
		}
	}

	/**
	 * Check whether object has attribute or not 
	 * @param  array   $model     object to check
	 * @param  string  $attribute attribute name
	 * @return boolean            true if has
	 */
	public static function has(array $model, $attribute)
	{
		if($model instanceof CActiveRecord) {
			return $model->hasAttribute($attribute);
		} elseif(is_object($model)) {
			return property_exists($model, $attribute);
		} elseif(is_array($model)) {
			return array_key_exists($attribute, $model);
		} else {
			throw new Exception('Unsapported operands type');
		}
	}
}