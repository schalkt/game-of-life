///<reference path="typings/tsd.d.ts" />
"use strict";
var App;
(function (App) {
    var app = angular.module('App');
    app.config([
        "AppConfigProvider",
        function (AppConfig) {
            var config = AppConfig.$get();
        }]);
    var AppController = (function () {
        function AppController(http, config) {
            this.http = http;
            this.config = config;
            this.step = 0;
            this.data = null;
            this.playing = false;
            var self = this;
            // first load
            this.load();
        }
        AppController.prototype.play = function () {
            this.step++;
            this.playing = true;
            this.load();
        };
        AppController.prototype.stop = function () {
            this.playing = false;
        };
        AppController.prototype.nextStep = function () {
            this.step++;
            this.load();
        };
        AppController.prototype.prevStep = function () {
            if (this.step < 1) {
                return;
            }
            this.step--;
            this.load();
        };
        AppController.prototype.load = function () {
            var _this = this;
            var self = this;
            this.http.post(this.config.api_url + '/step/' + 1, this.data).then(function (response) {
                self.data = JSON.parse(response.data);
                if (_this.playing) {
                    _this.play();
                }
            });
        };
        AppController.prototype.setPixel = function (x, y, r, g, b, a) {
            var index = (x + y * this.imageData.width) * 4;
            this.imageData.data[index + 0] = r;
            this.imageData.data[index + 1] = g;
            this.imageData.data[index + 2] = b;
            this.imageData.data[index + 3] = a;
        };
        // copy image to canvas
        AppController.prototype.toCanvas = function (data) {
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
        ;
        return AppController;
    }());
    App.AppController = AppController;
    app.controller("AppController", ["$http", "AppConfig", AppController]);
})(App || (App = {}));
//# sourceMappingURL=app.js.map