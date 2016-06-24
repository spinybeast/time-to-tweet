<?php
require __DIR__ . '/vendor/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

define('CONSUMER_KEY', '4QNsFzyH4aOJjK5yu4Hi4l4gS');
define('CONSUMER_SECRET', 'XRzwN3Ndl2FKX25bhB9Lox3tJn6qkvV8KbBSfOs4RORMFbaNfw');

$access_token = '742979641229643776-N9tPjhcszr1cyNGvSDVVZcYMKOSTk7m';
$access_token_secret = 'N0Uazf00UrICOBFbzFeCxDYRSy8zP8Ky1vnv74WNYTsBP';

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token, $access_token_secret);
$media1 = $connection->upload('media/upload', ['media' => __DIR__ . '/data/img/128.png']);
$media2 = $connection->upload('media/upload', ['media' => __DIR__ . '/data/img/2345.png']);

$parameters = [
    'status' => 'Meow Meow Meow',
    'media_ids' => implode(',', [$media1->media_id_string, $media2->media_id_string])
];
$result = $connection->post('statuses/update', $parameters);
$users = $connection->get('users/search?q=TwitterApi');
if ($connection->getLastHttpCode() === 200) {
    // Tweet posted succesfully
} else {
    var_dump($connection->getLastBody()); die();
}

var_dump($users); die();