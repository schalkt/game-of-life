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

        public step = 0;
        public data = null;
        private playing = false;

        constructor(private http : ng.IHttpService,
                    private config) {


            var self = this;

            // first load
            this.load();


        }

        public play() {

            this.step++;
            this.playing = true;
            this.load();

        }

        public stop() {

            this.playing = false;

        }

        public nextStep() {

            this.step++;
            this.load();

        }

        public prevStep() {

            if (this.step < 1) {
                return;
            }

            this.step--;
            this.load();

        }

        private load() {

            var self = this;

            this.http.post(this.config.api_url + '/step/' + 1, this.data).then((response : any) => {

                self.data = JSON.parse(response.data);
                if (this.playing) {
                    this.play();
                }

            });

        }


        private setPixel(x, y, r, g, b, a) {

            var index = (x + y * this.imageData.width) * 4;
            this.imageData.data[index + 0] = r;
            this.imageData.data[index + 1] = g;
            this.imageData.data[index + 2] = b;
            this.imageData.data[index + 3] = a;

        }

        // copy image to canvas
        private toCanvas(data) {

            var width = 120;
            var height = 120;

            var canvas = document.getElementById('golCanvas');
            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext("2d");
            this.imageData = context.createImageData(width, height);

            for (var x = 0; x < width; x++) {
                for (var y = 0; y < height; y++) {

                    this.setPixel(x, y, 0, 255, 255, 0);

                    if (data[x][y]) {
                        this.setPixel(x, y, 0, 255, 255, 0);
                    }
                }
            }

        };


    }

    app.controller("AppController", ["$http", "AppConfig", AppController]);

}

