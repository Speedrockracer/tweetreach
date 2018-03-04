<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\TweetAnalyzer;
use TwitterAPIExchange;

class TweetReachServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TwitterAPIExchange::class, function ($app) {
            return new TwitterAPIExchange(config('twitter'));
        });

        $this->app->singleton(TweetAnalyzer::class, function ($app) {
            return new TweetAnalyzer(
                $app->make('cache'),
                $app->make('TwitterAPIExchange')
            );
        });
    }
}
