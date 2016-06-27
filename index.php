<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/classes/TimeToTweet.php';

//TimeToTweet::instance()->postNews();
//TimeToTweet::instance()->followUsers('games');
//TimeToTweet::instance()->checkNotFollowBack();

TimeToTweet::instance()->sendMessagesToFriends();
TimeToTweet::instance()->done();
