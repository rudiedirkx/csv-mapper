<?php

namespace rdx\csvmapper;

use Exception;

class MissingColumnsException extends Exception {

	protected $columns = [];

	public function __construct( array $columns ) {
		parent::__construct('');

		$this->columns = $columns;
	}

	public function getColumns() {
		return $this->columns;
	}

}
