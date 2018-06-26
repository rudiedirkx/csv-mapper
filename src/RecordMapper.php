<?php

namespace rdx\csvmapper;

interface RecordMapper {

	public function map( array $record, $index );

}
