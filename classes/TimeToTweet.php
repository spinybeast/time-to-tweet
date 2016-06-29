<?php

use Abraham\TwitterOAuth\TwitterOAuth;

class TimeToTweet
{
    public static $connection;
    private static $instance;

    const CONSUMER_KEY = '4QNsFzyH4aOJjK5yu4Hi4l4gS';
    const CONSUMER_SECRET = 'XRzwN3Ndl2FKX25bhB9Lox3tJn6qkvV8KbBSfOs4RORMFbaNfw';
    const ACCESS_TOKEN = '742979641229643776-N9tPjhcszr1cyNGvSDVVZcYMKOSTk7m';
    const ACCESS_TOKEN_SECRET = 'N0Uazf00UrICOBFbzFeCxDYRSy8zP8Ky1vnv74WNYTsBP';

    private function __construct()
    {
        if (!self::$connection) {
            self::$connection = new TwitterOAuth(self::CONSUMER_KEY, self::CONSUMER_SECRET, self::ACCESS_TOKEN, self::ACCESS_TOKEN_SECRET);
        }
    }

    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function followUsers($query)
    {
        $users = $this->getUsers($query);
        foreach ($users as $user) {
            self::$connection->post('friendships/create', ['user_id' => $user->id]);
        }
    }

    public function getUsers($query)
    {
        return self::$connection->get('users/search', ['q' => $query, 'page' => 1, 'count' => 20]);
    }

    public function postNews()
    {
        $media = self::$connection->upload('media/upload', ['media' => $this->getRandomImage()]);
        $text = file_get_contents(__DIR__ . '/../data/statuses/text/text.txt');

        $parameters = [
            'status' => $text,
            'media_ids' => $media ? $media->media_id_string : null
        ];
        return self::$connection->post('statuses/update', $parameters);
    }

    public function getRandomImage()
    {
        $imgPath = __DIR__ . '/../data/statuses/img/';
        $allImages = scandir($imgPath);

        $images = [];
        foreach ($allImages as $image) {
            if (!strpos($image, '.png') and !strpos($image, '.jpg') and !strpos($image, '.gif')){
                continue;
            }
            $images[] = $image;
        }

        return $imgPath . $images[mt_rand(0, count($images) - 1)];
    }

    public function removeNotFollowers()
    {
        $friends = self::getFriends();
        $followers = self::getFollowers();

        $notFollowBack = array_diff($friends->ids, $followers->ids);
        foreach ($notFollowBack as $notFollow) {
            self::$connection->post('friendships/destroy', ['user_id' => $notFollow]);
        }

        return $notFollowBack;
    }

    public function sendMessagesToFriends()
    {
//        $friends = self::getFriends();
        $friends = self::$connection->get('users/lookup', ['screen_name' => 'jorastrah']);
        $message = file_get_contents(__DIR__ . '/../data/messages/text.txt');
        foreach ($friends as $friend) {
            self::sendMessage($friend, $message);
        }
    }


    private static function getFollowers()
    {
        return self::$connection->get('followers/ids', []);
    }

    private static function getFriends()
    {
        return self::$connection->get('friends/ids', []);
    }

    private static function sendMessage($friend, $message)
    {
        self::$connection->post('direct_messages/new', ['user_id' => $friend->id, 'text' => $message]);
    }

    public function done()
    {
        if (self::$connection->getLastHttpCode() === 200) {
            echo 'done!';
        } else {
            var_dump($connection->getLastBody()); die();
        }
    }


}