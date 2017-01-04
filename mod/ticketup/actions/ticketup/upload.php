<?php
/**
 * Elgg file uploader/edit action
 *
 * @package ElggFile
 */
//***********************************************
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '-1');
//***********************************************

// Get variables
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$desc = get_input("description");

/*$access_id = (int) get_input("access_id");
$container_guid = (int) get_input('container_guid', 0);*/
$access_id = elgg_extract('access_id', $vars, ACCESS_PRIVATE);
$container_guid = elgg_extract('container_guid', $vars);
if (!$container_guid) {
	$container_guid = elgg_get_logged_in_user_guid();
}

$guid = (int) get_input('file_guid');
$tags = get_input("tags");

if ($container_guid == 0) {
	$container_guid = elgg_get_logged_in_user_guid();
}

elgg_make_sticky_form('file');

// check if upload attempted and failed
if (!empty($_FILES['upload']['name']) && $_FILES['upload']['error'] != 0) {
	$error = elgg_get_friendly_upload_error($_FILES['upload']['error']);

	register_error($error);
	forward(REFERER);
}

// check whether this is a new file or an edit
$new_file = true;
if ($guid > 0) {
	$new_file = false;
}

if ($new_file) {
	// must have a file if a new file upload
	if (empty($_FILES['upload']['name'])) {
		$error = elgg_echo('file:nofile');
		register_error($error);
		forward(REFERER);
	}

	$file = new ElggFile();
	$file->subtype = "file";

	// if no title on new upload, grab filename
	if (empty($title)) {
		$title = htmlspecialchars($_FILES['upload']['name'], ENT_QUOTES, 'UTF-8');
	}

} else {
	// load original file object
	$file = get_entity($guid);
	if (!$file instanceof ElggFile) {
		register_error(elgg_echo('file:cannotload'));
		forward(REFERER);
	}
	/* @var ElggFile $file */

	// user must be able to edit file
	if (!$file->canEdit()) {
		register_error(elgg_echo('file:noaccess'));
		forward(REFERER);
	}

	if (!$title) {
		// user blanked title, but we need one
		$title = $file->title;
	}
}

$file->title = $title;
$file->description = $desc;
$file->access_id = $access_id;
$file->container_guid = $container_guid;
$file->tags = string_to_tag_array($tags);

// we have a file upload, so process it
if (isset($_FILES['upload']['name']) && !empty($_FILES['upload']['name'])) {

	$prefix = "file/";

	// if previous file, delete it
	if (!$new_file) {
		$filename = $file->getFilenameOnFilestore();
		if (file_exists($filename)) {
			unlink($filename);
		}
	}

	$filestorename = elgg_strtolower(time().$_FILES['upload']['name']);

	$file->setFilename($prefix . $filestorename);
	$file->originalfilename = $_FILES['upload']['name'];
	$mime_type = $file->detectMimeType($_FILES['upload']['tmp_name'], $_FILES['upload']['type']);

	$file->setMimeType($mime_type);
	$file->simpletype = elgg_get_file_simple_type($mime_type);

	// Open the file to guarantee the directory exists
	$file->open("write");
	$file->close();
	move_uploaded_file($_FILES['upload']['tmp_name'], $file->getFilenameOnFilestore());

	$guid = $file->save();

	if ($guid && $file->saveIconFromElggFile($file)) {
		$file->thumbnail = $file->getIcon('small')->getFilename();
		$file->smallthumb = $file->getIcon('medium')->getFilename();
		$file->largethumb = $file->getIcon('large')->getFilename();
	} else {
		$file->deleteIcon();
		unset($file->thumbnail);
		unset($file->smallthumb);
		unset($file->largethumb);
	}
} else {
	// not saving a file but still need to save the entity to push attributes to database
	$file->save();
}

// file saved so clear sticky form
elgg_clear_sticky_form('file');

//---------------------------------------------
//------- Se realiza la lectura del QR --------
include_once(elgg_get_plugins_path() . 'ticketup/lib/qr-decoder/QrReader.php');

$qrcode = new QrReader($file->getFilenameOnFilestore());
$qrText = $qrcode->text();

//$qrText = "Mercadona | 2016/12/29 | Leche | 2 | 1.2 | Pan | 3 | 1";

$arr_info = explode(":", $qrText);

$shop = trim($arr_info[0]);
$date = trim($arr_info[1]);

//Se elimina la tienda y la fecha, ademas de reindexar el array
unset($arr_info[0]);
unset($arr_info[1]);
$arr_info = array_values($arr_info);	

$products = array();
for ($i=0; $i < count($arr_info); $i+=3) { 
	$product = new ElggProduct();
	$product->setName(trim($arr_info[$i]));
	$product->setQuantity(trim($arr_info[$i+1]));
	$product->setPrice(trim($arr_info[$i+2]));
	$product->save();
	$products[] = $product;
}

//Se crea el objeto ElggTicketup y se les da valores a sus atributos
$ticketup = new ElggTicketup();
$ticketup->setShop($shop);
$ticketup->setDate($date);
$ticketup->setProducts($products);
$ticketup->setPhoto($file->guid);
$ticketup->save();

//-----------------------------------------------


// handle results differently for new files and file updates
if ($new_file) {
	if ($guid && $ticketup->guid) {
		$message = elgg_echo("file:saved");
		system_message($message);
		forward($ticketup->getURL());		
	} else {
		// failed to save file object - nothing we can do about this
		$error = elgg_echo("file:uploadfailed");
		register_error($error);
		forward(REFERER);
	}

} else {
	if ($guid && $ticketup->guid) {
		system_message(elgg_echo("file:saved"));
		forward($ticketup->getURL());	
	} else {
		register_error(elgg_echo("file:uploadfailed"));
		forward(REFERER);
	}
}


