<?php

// Debug purpose print $data to browser console
function console_log($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

// check if LastFM user exist
function checkUserExist($lastfmUser, $apiKey)
{

    $params = http_build_query(array(
        'method' => 'user.getinfo',
        'user' => $lastfmUser, // assume $lastfmUser is defined
        'api_key' => $apiKey, // assume $apiKey is defined
        'format' => 'json'
    ));

    $query = "https://ws.audioscrobbler.com/2.0/?" . $params;

    // $query = html_entity_decode("https://ws.audioscrobbler.com/2.0/?method=user.getinfo&user=$lastfmUser&api_key=$apiKey&format=json");

    try {
        $lastfmUserInfo = @file_get_contents($query);

        // Check for errors
        if ($lastfmUserInfo === false) {
            throw new Exception("Unable to fetch Last.fm user info.");
        } else {
            $lastfmUserInfoJson = json_decode($lastfmUserInfo, true);
        }

        if (!isset($lastfmUserInfoJson['user']['name'])) {
            throw new Exception("Unable to fetch Last.fm user info.");
        } else {
            return true;
        }
    } catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());
        // Return a generic error message
        return "An error occurred while fetching user info.";
    }
}

// Fetch Top Albums
function fetchtopalbums($lastfmUser, $apiKey, $period, $limit)
{

    $params = http_build_query(array(
        'method' => 'user.gettopalbums',
        'user' => $lastfmUser,
        'api_key' => $apiKey,
        'period' => $period,
        'limit' => $limit,
        'format' => 'json'
    ));

    $query = "https://ws.audioscrobbler.com/2.0/?" . $params;

    try {

        $lastfmdata = @file_get_contents($query);

        if ($lastfmdata === false) {
            throw new Exception("Unable to fetch Last.fm user Top Albums.");
        } else {
            $lastfmdatajson = json_decode($lastfmdata, true);
            $topalbums = $lastfmdatajson['topalbums']['album'];
        }
    } catch (Exception $e) {
        // Log the error message
        error_log($e->getMessage());
        // Return a generic error message
        return "An error occurred while fetching user top album.";
    }

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
            : false;

        // echo $extralarge_image_link;
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
            try {
                @imagecopyresampled($patchwork, $covers[$cols * $i + $j], $j * $imagesSideSize + $j, $i * $imagesSideSize + $i, 0, 0, $imagesSideSize + intval($noborder), $imagesSideSize + intval($noborder), imagesx($covers[$cols * $i + $j]), imagesy($covers[$cols * $i + $j]));
            } catch (\Throwable $th) {
                error_log($th->getMessage());
                return false;
            }
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

    return json_encode($response);
}

function returnJson($jsonData)
{
    header('Content-Type: application/json');
    echo json_encode($jsonData);
}

function returnImage($patchwork)
{
    header("Content-type: image/jpg");
    imagejpeg($patchwork);
}
