<?php
$homePath = $_SERVER['HOME'];
$CLIENT_ID = "PUT_CLIENT-ID_HERE";

$subreddit = $argv[1];
$schema = $argv[2];

$curl = curl_init();

curl_setopt_array($curl, array(
	CURLOPT_URL => "https://api.imgur.com/3/gallery/r/$subreddit",
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => "",
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 30,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => "GET",
	CURLOPT_HTTPHEADER => array(
	"authorization: Client-ID $CLIENT_ID"
	),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
	echo "cURL Error #:" . $err;
} else {
	$response = json_decode($response);
	$count = count($response->data);
	$random = rand(0, $count);

	if($response->data[$random]->type == "image/png")
		$ext = "png";
	else
		$ext = "jpg";

	$link = $response->data[$random]->link;
	$wallpaper = file_get_contents($link);
	file_put_contents("$homePath/iWallE_Wall.$ext", $wallpaper);

	$wallpaperPath = "file://$homePath/iWallE_Wall.$ext";
	shell_exec("gsettings set $schema picture-uri $wallpaperPath");
	echo "\nWallpaper changed..\n";
}