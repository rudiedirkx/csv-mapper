CSV mapper
====

Uses `league\csv` for reading CSV. Adds record & column mapping.

The first line is always used as header. You don't have to call `setHeaderOffset(0)`.

In all examples, `$reader` is iterated, not queried with a `Statement`:

	foreach ( $reader as $record ) {
		// $record is done, no more processing needed
		print_r($record);
	}

Record mappers
----

To keep only certain columns, and discard the rest, use the `KeepColumnsMapper`:

	$reader = \rdx\csvmapper\Reader::createFromPath($file);
	$reader->addMapper(new KeepColumnsMapper(['firstname', 'lastname', 'email']));

Create your own record mapper by implementing `RecordMapper`:

	class AddTimestampMapper implements RecordMapper {
		protected $time;
		public function __construct( $time ) {
			$this->time = $time;
		}
		public function map( array $record, $index ) {
			$record['timestamp'] = $this->time;
			return $record;
		}
	}
	
	$reader->addMapper(new AddTimestampMapper(time()));

Require columns
----

Require columns before processing iterating through the rows:

	$reader = \rdx\csvmapper\Reader::createFromPath($file);
	try {
		$reader->requireColumns(['email']);
	}
	catch ( MissingColumnsException $ex ) {
		echo implode(', ', $ex->getColumns());
	}

Column mappers
----

If several columns in the same row have the same mapping, use the `ColumnsMapper` record mapper with the `ColumnMapper` interface.

To format several date fields with your own date formatter:

	class DateFormatMapper implements ColumnMapper {
		public function map( array $record, $column, $index, $unset ) {
			$value = trim($record[$column]);
			$date = my_custom_date_maker($value);
			
			if ( !$date ) {
				// Invalid = NIL
				return null;
				
				// Invalid = skip field (remove from $record)
				return $unset;
				
				// Invalid = user error
				throw new LineException($index, "Invalid date: '$value'");
			}
			
			return $date;
		}
	}
	
	try {
		$reader = \rdx\csvmapper\Reader::createFromPath($file);
		$reader->addMapper(new ColumnsMapper(new DateFormatMapper(), ['birthdate', 'created_on', 'valid_until']));
	}
	catch ( LineException $ex ) {
		echo $ex->getMessage();
		print_r($ex->getRecord($reader));
	}

This will run the `DateFormatMapper` max 3 times for every row. The mapper runs only for fields that exist in `$record`.

If you want to make a field from nothing, use `RecordMapper` to alter the record. `ColumnMapper` only alters/unsets columns.
