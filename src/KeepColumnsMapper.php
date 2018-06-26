<?php

namespace rdx\csvmapper;

class KeepColumnsMapper implements RecordMapper {

	protected $columns = [];

	public function __construct( array $columns ) {
		$this->columns = array_flip($columns);
	}

	public function map( array $record, $index ) {
		$record = array_intersect_key($record, $this->columns);

		return $record;
	}

}
