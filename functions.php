<?php

// Debug purpose print $data to browser console
function console_log($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// check if LastFM user exist
function checkUserExist($lastfmUser, $apiKey)
{
    $query = "https://ws.audioscrobbler.com/2.0/?method=user.getinfo&user=$lastfmUser&api_key=$apiKey&format=json";
    try {
        $lastfmUserInfo = file_get_contents($query);
        
        // Check for errors
        if ($lastfmUserInfo === false) {
            throw new Exception("Unable to fetch Last.fm user info.");
        }
        
    $lastfmUserInfoJson = json_decode($lastfmUserInfo, true);

    if (isset($lastfmUserInfoJson['user']['name'])) {
        return true;
    }

    } catch (Exception $e) {
        // Handle the exception (log, display an error message, etc.)
        // For demonstration purposes, we'll return a string with the error message
        return $e->getMessage();
        // return false;
    }
}

// Fatch Top Albums
function fetchtopalbums($lastfmuser, $apikey, $period, $limit) {
    
    // create the url 
    // https://www.last.fm/api/show/user.gettopalbums
    $method = "user.gettopalbums";
    $apiurl = "https://ws.audioscrobbler.com/2.0/";

    $query = "$apiurl?method=$method&user=$lastfmuser&period=$period&limit=$limit&api_key=$apikey&format=json";

    $lastfmdata = file_get_contents($query);
    $lastfmdatajson = json_decode($lastfmdata, true);
    $topalbums = $lastfmdatajson['topalbums']['album'];

    return $topalbums;
}

// create albums cover array 
function createAlbumsCoverArray($topAlbums)
{
    $albumsCoverUrlList = array();
    foreach ($topAlbums as $topAlbum) {
        // check if extralarge image exist 
        $extralarge_image_link = isset($topAlbum['image'][3]['#text']) && !empty($topAlbum['image'][3]['#text'])
            ? parse_url($topAlbum['image'][3]['#text'])
            : null;

        if ($extralarge_image_link) {
            $image_filename = pathinfo($extralarge_image_link['path'], PATHINFO_BASENAME);
            $original_image_link = "https://" . $extralarge_image_link['host'] . "/i/u/" . $image_filename;
            $albumsCoverUrlList[] = $original_image_link;
        }
    }

    return $albumsCoverUrlList;
}

function createImagesFromUrls($urls)
{

    $images = array();

    foreach ($urls as $url) {
        $fileExtension = pathinfo($url, PATHINFO_EXTENSION);

        switch ($fileExtension) {
            case 'jpg':
                $images[] = imagecreatefromjpeg($url);
                break;
            case 'png':
                $images[] = imagecreatefrompng($url);
                break;
            case 'gif':
                $images[] = imagecreatefromgif($url);
                break;
                // Add more cases for other supported image formats if needed
        }
    }

    return $images;
}

function createPatchwork($imagesSideSize, $patchworkHeight, $patchworkWidth, $noborder, $cols, $rows, $covers)
{

    // $patchworkWidth = $imagesSideSize * $cols + ($cols - 1); // 299 is the max size of the Last.fm profile left column ;)
    // $patchworkHeight = $imagesSideSize * $rows + ($rows - 1);

    // create the "empty" patchwork
    $patchwork = imagecreatetruecolor($patchworkWidth, $patchworkHeight);

    if (!$noborder) {
        // create a white color (reminds me of SDL ^^)
        $white = imagecolorallocate($patchwork, 255, 255, 255);
        // we fill our patchwork by the white color
        imagefilltoborder($patchwork, 0, 0, $white, $white);
    }

    // now we "parse" our images in the patchwork, while resizing them :]
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            imagecopyresampled($patchwork, $covers[$cols * $i + $j], $j * $imagesSideSize + $j, $i * $imagesSideSize + $i, 0, 0, $imagesSideSize + intval($noborder), $imagesSideSize + intval($noborder), imagesx($covers[$cols * $i + $j]), imagesy($covers[$cols * $i + $j]));
        }
    }

    return $patchwork;
}

function createImageJsonData($fileName, $PatchworkWidth, $PatchworkHeight)
{
    $response = [
        'imagePath' => $fileName,
        'width' => $PatchworkWidth,
        'height' => $PatchworkHeight,
    ];

    // header('Content-Type: application/json');
    return json_encode($response);
}
?>