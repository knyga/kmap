<?php

class CMap
{
	public static function group(array $models, $attribute)
	{
		$output=array();
		for($i=0; $i<count($models); $i++)
		{
			if($models[$i] instanceof CActiveRecord)
				$output[$models[$i]->getAttribute($attribute)][]=$models[$i];
			elseif(is_object($models[$i]))
				$output[$models[$i]->$attribute][]=$models[$i];
			elseif(is_array($models[$i]))
				$output[$models[$i][$attribute]][]=$models[$i];
			else
				throw new Exception('Unsapported operands type');
		}
		return $output;
	}

	public static function filter(array $models, $attribute)
	{
		return array_map(function($data) use($attribute) {
			if($data instanceof CActiveRecord)
				return $data->getAttribute($attribute);
			elseif(is_object($data))
				return $data->$attribute;
			elseif(is_array($data))
				return $data[$attribute];
			else
				throw new Exception('Unsapported operands type');
		},$models);
	}
}