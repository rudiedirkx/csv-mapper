<?php

namespace rdx\csvmapper;

use Iterator;
use League\Csv\MapIterator;
use League\Csv\Reader as BaseReader;

class Reader extends BaseReader {

	/** @var RecordMapper[] */
	protected $mappers = [];

	protected function __construct( $document ) {
		parent::__construct($document);

		$this->setHeaderOffset(0);
	}

	public function getHeader(): array {
		return $this->trimRecord(parent::getHeader());
	}

	protected function trimRecord( array $record ) {
		return array_map('trim', $record);
	}

	public function getRecord( $offset ) {
		$header = $this->getHeader();
		$row = $this->trimRecord($this->seekRow($offset));
		return $header ? array_combine($header, $row) : $row;
	}

	public function getRecords( array $header = [] ): Iterator {
		$iterator = parent::getRecords($header);

		return new MapIterator($iterator, function(array $record, $index) {
			$record = $this->trimRecord($record);
			foreach ( $this->mappers as $mapper ) {
				$record = $mapper->map($record, $index);
			}

			return $record;
		});
	}

	public function addMapper( RecordMapper $mapper ) {
		$this->mappers[] = $mapper;

		return $this;
	}

	public function requireColumns( array $requireColumns ) {
		$haveColumns = $this->getHeader();
		if ( $missing = array_diff($requireColumns, $haveColumns) ) {
			throw new MissingColumnsException($missing);
		}
	}

}
