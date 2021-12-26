<?php

namespace TgUtils;

class Stack {

	protected $stack;

	public function __construct(...$values) {
		$this->stack = array();
		foreach ($values AS $value) {
			$this->push($value);
		}
	}

	public function isEmpty() {
		return count($this->stack) == 0;
	}

	public function size() { 
		 return count($this->stack);
	}

	public function push($value) {
		if ($value != NULL) array_push($this->stack, $value);
		else throw new \Exception('Cannot push NULL element to stack');
	}

	public function pop() {
		if (!$this->isEmpty()) {
			return array_pop($this->stack);
		}
		return NULL;
	}

	public function peek() {
		if (!$this->isEmpty()) {
			return $this->stack[count($this->stack)-1];
		}
		return NULL;
	}
}

