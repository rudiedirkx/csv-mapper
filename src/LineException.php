<?php

namespace rdx\csvmapper;

use Exception;

class LineException extends Exception {

	public function __construct( $line, $message ) {
		parent::__construct($message, $line);
	}

	public function getCsvLine() {
		return $this->getCode() + 1;
	}

	public function getRecord( Reader $reader ) {
		return $reader->getRecord($this->getCode());
	}

}
