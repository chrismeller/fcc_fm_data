<?php

	$filename = '/Users/chris/Dropbox/FCC Data/FM Licensing Database/fm/fm.dbf';
	
	$dest_csv = '/Users/chris/Dropbox/FCC Data/FM Licensing Database/fm.tsv';
	
	// 0 == read-only
	$db = dbase_open( $filename, 0 );
	
	if ( !$db ) {
		die('Unable to open dBase file.');
	}
	
	// get the list of fields
	$fields = dbase_get_header_info( $db );
	
	$f_quote = array();
	$f_names = array();
	foreach ( $fields as $field ) {
		$f_names[] = $field['name'];
		if ( $field['type'] == 'character' ) {
			$f_quote[ $field['name'] ] = true;
		}
		else {
			$f_quote[ $field['name'] ] = false;
		}
	}
	
	// add 'deleted' to both arrays. it's a field, but it's not in the header
	$f_quote['deleted'] = false;
	$f_names[] = 'deleted';
	
	// write the column headers to the file
	$field_headers = '"' . implode( '"' . "\t" . '"', $f_names ) . '"' . "\n";
	file_put_contents( $dest_csv, $field_headers, FILE_APPEND );
	
	// get the number of records in the db
	$records = dbase_numrecords( $db );
	
	echo 'Records: ' . $records;
	
	for ( $i = 1; $i <= $records; $i++ ) {
		
		// fetch the record
		$row = dbase_get_record_with_names( $db, $i );
		
		if ( !$row ) {
			echo 'Unable to fetch record ' . $i . "\n";
			break;	// break out of the loop and proceed to close()
		}
		else {
			echo 'Writing row ' . $i . "\n";
			
			$f_row = array();
			
			foreach ( $row as $k => $v ) {
				
				// trim whitespace from the value
				$v = trim( $v );
				
				if ( $f_quote[ $k ] ) {
					$f_row[ $k ] = '"' . $v . '"';
				}
				else {
					$f_row[ $k ] = $v;
				}
				
			}
			
			$row = implode("\t", $f_row);
			
			file_put_contents( $dest_csv, $row . "\n", FILE_APPEND );
		}
		
	}
	
	// close the file at the end
	dbase_close( $db );

?>