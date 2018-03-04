<template>
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="clearfix">
                    <form v-on:submit.prevent="onSubmit">
                        <h1>Analyze the reach of you tweet</h1>
                        <div class="form-group">
                            <label for="tweetUrl">Tweet url (start with https://twitter.com/)</label>
                            <input v-model="form.tweetUrl" class="form-control" name="tweetUrl" type="text" />
                        </div>
                        <button class="btn btn-default pull-right" type="submit">Analyze</button>
                    </form>
                </div>
                <transition name="fade">

                    <div v-if="analyzing">
                        <p class="center">
                            Analyzing tweet...
                        </p>
                    </div>

                    <div class="card" v-if="error">
                        <div class="card-heading">
                            <h3>Oh noes!</h3>
                            <p>Analyzing tweet failed. Please check your input url.</p>
                        </div>
                    </div>

                    <div class="card" v-if="tweet">
                        <h3 class="card-heading">{{tweet.text}}</h3>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4>Calculated reach</h4>
                                    <p><strong>Retweets:</strong> {{tweet.retweetCount}}</p>
                                    <p><strong>Reach:</strong> {{tweet.reach}}</p>
                                </div>
                                <div class="col-sm-6 clearfix">
                                    <img v-bind:src="tweet.userPictureUrl" alt="Profile" class="img-circle pull-left">
                                    <h4>{{tweet.userName}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            Last updated: {{tweet.updatedTime}}
                        </div>
                    </div>

                </transition>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';

    var data = {
        form: {
            tweetUrl: "",
        },
        tweet: null,
        analyzing: false,
        error: false,
    }

    export default {
        data: function () {
            return data
        },

        methods: {
            onSubmit(event) {
                this.tweet = null;
                this.analyzing = true;
                this.error = false;

                axios.get('/analyze?tweetUrl=' + data.form.tweetUrl)
                    .then(response => {
                        this.tweet = response.data
                        this.analyzing = false;
                    })
                    .catch(e => {
                        this.analyzing = false;
                        this.error = true;
                    })
            }
        }
    }
</script>