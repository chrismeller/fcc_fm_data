<?php

	$filename = '/Users/chris/Dropbox/FCC Data/FM Licensing Database/fm.tsv';
	
	$lines = file( $filename );
	
	$data = tsv_to_array( $lines );
	
	$dist = array();
	
	foreach ( $data as $record ) {
		
		$callsign = $record['CALLSIGN'];
		$frequency = floatval( $record['FREQUENCY'] );	// trim the "  MHz"
		
		// cast frequency as a string so we can use it properly as the array key
		$frequency = (string) $frequency;
		
		$dist[ $frequency ][] = $callsign;
		
	}
	
	$count = array();
	foreach ( $dist as $k => $v ) {
		
		$count[ $k ] = count( $dist[ $k ] );
		
		arsort( $count );
		
	}
	
	print_r( $count );
	
	function tsv_to_array ( $lines ) {
		
		// shift the field names off the top
		$f = array_shift( $lines );
		
		// trim any whitespace or newlines
		$f = trim( $f );
		
		$f = explode( "\t", $f );
		
		$fields = array();
		foreach ( $f as $field ) {
			$fields[] = trim( $field, '"' );	// trim " from both sides, if it's there
		}
		
		$result = array();
		foreach ( $lines as $l ) {
			
			$l = explode( "\t", $l );
			
			$line = array();
			foreach ( $l as $field ) {
				$line[] = trim( $field, '"' );	// trim " from both sides, if it's there
			}
			
			$line = array_combine( $fields, $line );
			
			$result[] = $line;
			
		}
		
		return $result;
		
	}

?>