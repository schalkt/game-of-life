///<reference path="typings/tsd.d.ts" />

"use strict";

module App {

    var app : ng.IModule = angular.module('App');

    app.config([
        "AppConfigProvider",
        function (AppConfig) {

            var config = AppConfig.$get();

        }]);

    export class AppController {

        public step : number = 0;
        public data = null;
        public width : number = 80;
        public height : number = 20;
        private playing : boolean = false;
        public edit : boolean = false;

        constructor(private http : ng.IHttpService,
                    private config) {


            this.load();

        }

        // play next step
        public play() {

            this.step++;
            this.playing = true;
            this.load();

        }

        // stop
        public stop() {

            this.playing = false;

        }

        // load next step
        public nextStep() {

            this.step++;
            this.load();

        }

        // editor cell toggle
        public toggle(x, y) {

            if (!this.edit) {
                return;
            }
            this.data[x][y] = !this.data[x][y];

        }

        // load grid data
        private load() {

            var self = this;

            this.http.post(this.config.api_url + '/step/' + 1, this.data).then((response : any) => {

                self.data = JSON.parse(response.data);

                // recursive play
                if (this.playing) {
                    this.play();
                }

            });

        }

    }

    app.controller("AppController", ["$http", "AppConfig", AppController]);

}

