<?php

namespace src\Models;

class Model
{

	private array $attributes;

	/**
	 * @param array $properties
	 */
	public function __construct(array $properties = [])
	{
		$this->attributes = $properties;
	}

	/**
	 * @param string $attribute
	 * @param mixed $value
	 * @return mixed
	 */
	public function __set(string $attribute, mixed $value)
	{
		return $this->attributes[$attribute] = $value;
	}

	/**
	 * @param string $attribute
	 * @return mixed|null
	 */
	public function __get(string $attribute)
	{
		if (array_key_exists($attribute, $this->attributes)) {
			return $this->attributes[$attribute];
		}
		return null;
	}
}
