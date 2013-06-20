<?php

namespace Zule\Tools;

class ArrayUtil
{
	static function any($array)
	{
		return $array[array_keys($array)[0]];
	}
}
