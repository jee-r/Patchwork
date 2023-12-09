<?php
// import functions
require_once '../src/functions.php';

if (!isset($_ENV["LASTFM_API_KEY"])) {
    // load Lastfm APIKEY from .env
    require_once realpath(__DIR__ . "/../vendor/autoload.php");
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/..");
    $dotenv->load();
}

$apiKey = $_ENV['LASTFM_API_KEY'];

// GET Params;
$lastfmUser = strtolower($_GET["username"]);
$period     = $_GET["period"];
$rows       = $_GET["rows"];
$cols       = $_GET["cols"];
$imagesSize = $_GET["imageSize"];
$noborder   = (bool)(isset($_GET["noborder"]) && $_GET["noborder"]);

// return json data or image file
$json = (bool)(isset($_GET["json"]) && $_GET["json"]);

// TODO implement a method which verify number of available cover
// and increase the limit incase there is not enough available
// For the moment we get 1/3 more albums than required 
$limit = ceil(($cols * $rows) + ( ($cols * $rows) / 3 ));

// Fallback if imagesSize is not set
(isset($imagesSize)) ? $imagesSideSize = $imagesSize : $imagesSideSize = 99;

// Calculate patchwork size 
$patchworkWidth = $imagesSideSize * $cols + ($cols - 1); // 299 is the max size of the Last.fm profile left column ;)
$patchworkHeight = $imagesSideSize * $rows + ($rows - 1);

// check if username is valid
if (preg_match('/^[a-zA-Z0-9_.-]+$/', $lastfmUser) !== 1) {
    $response = [
        'error' => "Invalid Username",
    ];
    returnJson($response);
    exit(0);
}

// check if username exist
if (checkUserExist($lastfmUser, $apiKey) !== true) {
    $response = [
        'error' => checkUserExist($lastfmUser, $apiKey),
    ];
    returnJson($response);
    exit(0);
}

// Fetch top albums fron LastFM
$topAlbums = fetchtopalbums($lastfmUser, $apiKey, $period, $limit);

// Check if albums is not empty 
if (!is_array($topAlbums) || (is_array($topAlbums) && empty($topAlbums))) {
    // if (empty($topAlbums) ) {
    $response = [
        'error' => "User does not have scrobbled any albums",
    ];
    returnJson($response);
    exit(0);
}

$border = $noborder ? "noborder" : "border";
// create Hash filename to avoid duplication
$topAlbumsDataHash = hash('md5', json_encode($topAlbums));
// $fileName = "images/$lastfmUser_$period_$rows_$cols_$imagesSize_$border-hash_$topAlbumsDataHash.jpg";
$fileName = "images/{$lastfmUser}_{$period}_{$rows}_{$cols}_{$imagesSize}_{$border}-hash_{$topAlbumsDataHash}.jpg";

// If file exist return existing data or image 
if (file_exists($fileName)) {
    $response = [
        'imagePath' => $fileName,
        'width' => $patchworkWidth,
        'height' => $patchworkHeight,
    ];

    // $patchwork = file_get_contents($fileName);
    $patchwork = imagecreatefromjpeg($fileName);
    // console_log($patchwork);

    // return json if requested else return image
    if ($json) {
        // return json data
        returnJson($response);
    } else {
        // display the image
        returnImage($patchwork);
    }
} else {
    // Else Generate a new patchwork 
    $albumsCovers = createAlbumsCoverArray($topAlbums);
    $covers = createImagesFromUrls($albumsCovers);
    $patchwork = createPatchwork($imagesSideSize, $patchworkHeight, $patchworkWidth, $noborder, $cols, $rows, $covers);
    // console_log(gettype($patchwork));
    
    if ($patchwork === false) {
        $response = [
            'error' => "An error occurred while generating patchwork.",
        ];
        returnJson($response);
        exit(0);
    }
    
    // save the image into a file
    imagejpeg($patchwork, $fileName);

    $response = [
        'imagePath' => $fileName,
        'width' => $patchworkWidth,
        'height' => $patchworkHeight,
    ];

    // return json if requested else return image
    if ($json) {
        returnJson($response);
    } else {
        returnImage($patchwork);
    }
}
