<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TweetAnalyzer;
use Exception;

class TweetsController extends Controller {

    protected $tweetAnalyzer;

    public function __construct(TweetAnalyzer $tweetAnalyzer) {
        $this->tweetAnalyzer = $tweetAnalyzer;
    }

    public function getIndex() {
        return view('tweets.index');
    }

    public function getAnalyze(Request $request) {
        $validatedData = $request->validate([
            'tweetUrl' => 'required|url',
        ]);

        $tweetUrl = $validatedData['tweetUrl'];
        
        // Get id from url
        $array = explode('/', $tweetUrl);

        return json_encode(
            $this->tweetAnalyzer->analyzeTweet(end($array))
        );
    }

    // protected function buildFailedValidationResponse(Request $request, array $errors)
    // {
    //     if ($request->expectsJson()) {
    //         return new JsonResponse($errors, 422);
    //     }

    //     return parent::buildFailedValidationResponse($request, $errors);
    // }
}
