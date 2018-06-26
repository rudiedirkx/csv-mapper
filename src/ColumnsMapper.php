<?php

namespace rdx\csvmapper;

use Exception;

class ColumnsMapper implements RecordMapper {

	protected $mapper;
	protected $columns = [];

	public function __construct( ColumnMapper $mapper, array $columns ) {
		$this->mapper = $mapper;
		$this->columns = $columns;
	}

	public function map( array $record, $index ) {
		$unset = $this;

		foreach ( $this->columns as $column ) {
			if ( isset($record[$column]) ) {
				$value = $this->mapper->map($record, $column, $index, $unset);
				if ( $value === $unset ) {
					unset($record[$column]);
				}
				else {
					$record[$column] = $value;
				}
			}
		}

		return $record;
	}

}
