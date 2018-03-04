# Tweet reach demo web app

The app is run in a docker container. To run make sure docker is installed and running.
From the command line execute the following commands:

* git clone https://github.com/Speedrockracer/tweetreach.git tweetreach
* cd tweetreach
* npm install
* npm run development
* docker run --rm -v $(pwd):/app composer/composer install
* docker-compose up
* visit http://localhost:8080 in your browser.

The compose up command might take a while the first time.
Also the page load time is a bit slow because of the filewatcher docker uses. Didn't have time to fix that.
