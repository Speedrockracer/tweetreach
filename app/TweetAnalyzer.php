<?php

namespace App;

use Illuminate\Cache\CacheManager;
use TwitterAPIExchange;
use Exception;

class TweetAnalyzer {

    const REMEMBER_TIME = 120; // Minutes

    protected $twitterApi;
    protected $cache;

    public function __construct(CacheManager $cache, TwitterAPIExchange $twitterApi) {
        $this->cache = $cache;
        $this->twitterApi = $twitterApi;
    }

    public function analyzeTweet($id) {
        return $this->cache->remember($id, TweetAnalyzer::REMEMBER_TIME, function () use ($id) {
            return $this->createTweet(
                $this->getTweetData($id),
                $this->getRetweetData($id)
            );
        });
    }

    protected function getTweetData($id) {
        $getTweetUrl = "https://api.twitter.com/1.1/statuses/show/$id.json";
        $rawResult = $this->twitterApi->buildOauth($getTweetUrl, 'GET')
            ->performRequest();

        $parsedResult = json_decode($rawResult);

        if (is_object($parsedResult) && !isset($parsedResult->errors)) {
            return $parsedResult;
        } else {
            throw new Exception("Invalid tweet");
        }
    }

    protected function getRetweetData($id) {
        $getTweetStatusUrl = "https://api.twitter.com/1.1/statuses/retweets/$id.json";
        $rawResult = $this->twitterApi->buildOauth($getTweetStatusUrl, 'GET')
            ->performRequest();

        $parsedResult = json_decode($rawResult);

        if (is_array($parsedResult)) {
            return $parsedResult;
        } else {
            throw new Exception("Invalid tweet");
        }
    }

    protected function createTweet($tweetData, $retweets) {
        $tweet = new Tweet();

        $tweet->updatedTime = date("D M d, Y G:i");
        $tweet->text = $tweetData->text;
        try {
            $tweet->userName = $tweetData->user->screen_name;
            $tweet->userPictureUrl = $tweetData->user->profile_image_url_https;
        } catch (Exception $e) {} // Invalid json -- ignore.

        $tweet->retweetCount = count($retweets);
        $tweet->reach = $this->calculateReach($retweets);

        return $tweet;
    }

    protected function calculateReach(array $retweets) {
        return array_reduce($retweets, function ($carry, $item) {
            try {
                $carry += $item->user->followers_count;
            } catch (Exception $e) {} // Invalid json -- ignore.

            return $carry;
        }, 0);
    }
}
