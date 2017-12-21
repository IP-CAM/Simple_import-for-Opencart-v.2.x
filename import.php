<?php
require('config.php');
$link = mysqli_connect(DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE) or die('Connect error');

$h = fopen('import.csv','r');
while($r = fgetcsv($h,0,';')) {
	$id = $r[0];
	$name = str_replace('"', '`', $r[1]);
	$desc = str_replace('"', '`', $r[2]);
	$img = $r[3] ? $r[3] : false;
	$img_name = end(explode('/',$img));

	$product = "
		INSERT INTO `oc_product` 
		(
			`product_id`, 
			`model`, 
			`sku`, 
			`upc`, 
			`ean`,
			`jan`,
			`isbn`,
			`mpn`,
			`location`, 
			`quantity`, 
			`stock_status_id`, 
			`image`, `manufacturer_id`, 
			`shipping`, 
			`price`, 
			`points`, 
			`tax_class_id`, 
			`date_available`, 
			`weight`, 
			`weight_class_id`, 
			`length`, 
			`width`, 
			`height`, 
			`length_class_id`, 
			`subtract`, `minimum`, 
			`sort_order`, `status`, 
			`viewed`, `date_added`, 
			`date_modified`
		) VALUES (
			NULL, 
			'$name', 
			'$id', 
			'', 
			'', 
			'', 
			'', 
			'', 
			'',  
			9999, 
			7,
			'import/$img_name', 
			0, 
			1, 
			'100.0000', 
			200, 
			9, 
			'2009-02-03', 
			'0', 
			2, 
			'0.00000000', 
			'0.00000000', 
			'0.00000000', 
			1, 
			1, 
			1, 
			0, 
			1, 
			0, 
			'2009-02-03 16:06:50', 
			'2011-09-30 01:05:39');

		";
	mysqli_query($link,$product) or die(mysqli_error($link));
	$product_id = mysqli_insert_id($link);
	$product_description = "
		INSERT INTO `oc_product_description` 
		(
			`product_id`, 
			`language_id`, 
			`name`, 
			`description`, 
			`tag`, 
			`meta_title`, 
			`meta_description`, 
			`meta_keyword`
		) VALUES (
			$product_id, 
			2, 
			'', 
			'', '', '$name', '$desc', 
			'');

	";
	mysqli_query($link,$product_description) or die(mysqli_error($link));

	$product2store = "INSERT INTO `oc_product_to_store` (`product_id`, `store_id`) VALUES ($product_id, 0);";

	mysqli_query($link,$product2store) or die(mysqli_error($link));

}