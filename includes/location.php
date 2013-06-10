<?php

new tpc_database_functions;

class tpc_database_functions{

	function tpc_database_functions(){
		$this->__construct();
	}
	
	function __construct(){ 
		$this->add_tables();
		$this->add_location_data();
	}
	
	function add_tables(){
	
		if(get_option('currency_added') != 'added'){
	
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			
			global $wpdb;
					
			$sql = "
			CREATE TABLE  `" . TPC_location_data_table . "` (
			`id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			`country` varchar(255) NOT NULL DEFAULT '',
			`isocode` char(2) DEFAULT '',
			`currency` varchar(255) NOT NULL DEFAULT '',
			`symbol` varchar(10) NOT NULL DEFAULT '',
			`symbol_html` varchar(10) NOT NULL DEFAULT '',
			`code` char(3) NOT NULL DEFAULT '',
			`has_regions` char(1) NOT NULL DEFAULT '0',
			`tax` varchar(8) NOT NULL DEFAULT '',
			`continent` varchar(20) NOT NULL DEFAULT '',
			`visible` varchar(1) NOT NULL DEFAULT '1',
			PRIMARY  KEY (`id`)
			);
			";
			
			dbDelta($sql);
		
		}
		
	}
	
	function add_location_data() {
		global $wpdb;
		if(get_option('currency_added') != 'added'){
			require_once(dirname( __FILE__ ) . '/stuff/add_location_data.php');
			update_option('currency_added', 'added');
		}
	}	

}

function tpc_countries(){

	global $wpdb;
	$sql = "select * from " . $wpdb->prefix . "jm_location_data order by country asc";
	$locations = $wpdb->get_results($sql);
	$countries_array = array();

	foreach($locations as $location){
		$countries_array[$location->isocode]=$location->country;
	}

	return $countries_array;

}

function tpc_countries_continent($cc, $cap = false){

	global $wpdb;
	$sql = "select continent from " . $wpdb->prefix . "jm_location_data where isocode = '" . $cc ."'";
	$continent_result = $wpdb->get_row($sql);
	$continent = $continent_result->continent;

	if($cap){
		return ucfirst($continent);
	}else{
		return $continent;
	}

}

function tpc_countries_country($cc){

	global $wpdb;
	$sql = "select country from " . $wpdb->prefix . "jm_location_data where isocode = '" . $cc ."'";
	$country_result = $wpdb->get_row($sql);
	$country = $country_result->country;

	return $country;

}

function tpc_countries_currency($cc){

	global $wpdb;
	$sql = "select currency from " . $wpdb->prefix . "jm_location_data where isocode = '" . $cc ."'";
	$country_result = $wpdb->get_row($sql);
	$country = $country_result->currency;

	return $country;

}

function tpc_countries_currency_symbol($cc){

	global $wpdb;
	$sql = "select symbol from " . $wpdb->prefix . "jm_location_data where isocode = '" . $cc ."'";
	$country_result = $wpdb->get_row($sql);
	$country = $country_result->symbol;

	return $country;

}
