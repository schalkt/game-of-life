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
            this.width = 80;
            this.height = 20;
            this.playing = false;
            this.edit = false;
            this.load();
        }
        // play next step
        AppController.prototype.play = function () {
            this.step++;
            this.playing = true;
            this.load();
        };
        // stop
        AppController.prototype.stop = function () {
            this.playing = false;
        };
        // load next step
        AppController.prototype.nextStep = function () {
            this.step++;
            this.load();
        };
        AppController.prototype.randomize = function () {
            this.playing = false;
            this.data = null;
            this.load();
        };
        // editor cell toggle
        AppController.prototype.toggle = function (x, y) {
            if (!this.edit) {
                return;
            }
            this.data[x][y] = !this.data[x][y];
        };
        // load grid data
        AppController.prototype.load = function () {
            var _this = this;
            var self = this;
            this.http.post(this.config.api_url + '/step/' + 1, this.data).then(function (response) {
                self.data = JSON.parse(response.data);
                // recursive play
                if (_this.playing) {
                    _this.play();
                }
            });
        };
        return AppController;
    }());
    App.AppController = AppController;
    app.controller("AppController", ["$http", "AppConfig", AppController]);
})(App || (App = {}));
//# sourceMappingURL=app.js.map