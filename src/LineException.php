<?php

namespace rdx\csvmapper;

use Exception;

class LineException extends Exception {

	protected $record;

	public function __construct( $line, $message, array $record = null ) {
		parent::__construct($message, $line);

		$this->record = $record;
	}

	public function getCsvLine() {
		return $this->getCode() + 1;
	}

	public function getRecord( Reader $reader ) {
		return $this->record ?? $reader->getRecord($this->getCode());
	}

}
