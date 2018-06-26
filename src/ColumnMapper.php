<?php

namespace rdx\csvmapper;

interface ColumnMapper {

	public function map( array $record, $column, $index, $unset );

}
