/**
 * Created by zhu yongxuan on 2016/8/5.
 */

'use strict';

angular.module('ndzxApp')
    .constant("baseURL", "https://ndzxdc.herokuapp.com/api/")
    .factory('adminFactory', ['$resource', 'baseURL', function ($resource, baseURL) {

        return $resource(baseURL + "admins/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])
    

    .factory('depositFactory', ['$resource', 'baseURL', function ($resource, baseURL) {

        return $resource(baseURL + "deposits/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])

    .factory('dinnerFactory', ['$resource', 'baseURL', function ($resource, baseURL) {

        return $resource(baseURL + "dinners/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])

    .factory('dishFactory', ['$resource', 'baseURL', function ($resource, baseURL) {


        return $resource(baseURL + "dishes/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])


    .factory('menuFactory', ['$resource', 'baseURL', function ($resource, baseURL) {


        return $resource(baseURL + "menus/:id", null, {
            'get' : {
                method: 'Get',
                isArray: true
            },
            'update': {
                method: 'PUT'
            }
        });

    }])

    .factory('orderFactory', ['$resource', 'baseURL', function ($resource, baseURL) {


        return $resource(baseURL + "orders/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])


    .factory('userFactory', ['$resource', 'baseURL', function ($resource, baseURL) {


        return $resource(baseURL + "users/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])


    .factory('userinfoFactory', ['$resource', 'baseURL', function ($resource, baseURL) {


        return $resource(baseURL + "userinfos/:id", null, {
            'update': {
                method: 'PUT'
            }
        });

    }])

    .factory('$localStorage', ['$window', function ($window) {
        return {
            store: function (key, value) {
                $window.localStorage[key] = value;
            },
            get: function (key, defaultValue) {
                return $window.localStorage[key] || defaultValue;
            },
            remove: function (key) {
                $window.localStorage.removeItem(key);
            },
            storeObject: function (key, value) {
                $window.localStorage[key] = JSON.stringify(value);
            },
            getObject: function (key, defaultValue) {
                return JSON.parse($window.localStorage[key] || defaultValue);
            }
        }
    }])

    .factory('AuthFactory', ['$resource', '$http', '$localStorage', '$rootScope', '$window', 'baseURL', 'ngDialog', '$state', function($resource, $http, $localStorage, $rootScope, $window, baseURL, ngDialog, $state){

        var authFac = {};
        var TOKEN_KEY = 'Token';
        var isAuthenticated = false;
        var username = '';
        var authToken = undefined;


        function loadUserCredentials() {
            var credentials = $localStorage.getObject(TOKEN_KEY,'{}');
            if (credentials.username != undefined) {
                useCredentials(credentials);
            }
        }

        function storeUserCredentials(credentials) {
            $localStorage.storeObject(TOKEN_KEY, credentials);
            useCredentials(credentials);
        }

        function useCredentials(credentials) {
            isAuthenticated = true;
            username = credentials.username;
            authToken = credentials.token;

            // Set the token as header for your requests!
            $http.defaults.headers.common['authorization'] = 'bearer ' + authToken;
        }

        function destroyUserCredentials() {
            authToken = undefined;
            username = '';
            isAuthenticated = false;
            $http.defaults.headers.common['authorization'] = 'bearer ' + authToken;
            $localStorage.remove(TOKEN_KEY);
        }

        authFac.login = function(loginData) {

            console.log(loginData);

            $resource(baseURL + "signin")
                .save(loginData,
                    function(response) {
                        if (response.type == 'fail') {
                            isAuthenticated = false;

                            var message = '\
                <div class="ngdialog-message">\
                <div><h3>登录失败</h3></div>' +
                                '<div><p>' +  '用户名或者账号错误' + '</p></div>' +
                                '<div class="ngdialog-buttons">\
                                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click=confirm("OK")>确定</button>\
                                </div>'

                            ngDialog.openConfirm({ template: message, plain: 'true'});
                        } else {
                            storeUserCredentials({username:loginData.username, token: response.token});
                            $rootScope.$broadcast('login:Successful');
                        }
                    },
                    function(response){
                        isAuthenticated = false;

                        var message = '\
                <div class="ngdialog-message">\
                <div><h3>登录失败</h3></div>' +
                            '<div><p>' +  '用户名或者账号错误' + '</p></div>' +
                            '<div class="ngdialog-buttons">\
                                <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click=confirm("OK")>确定</button>\
                            </div>'

                        ngDialog.openConfirm({ template: message, plain: 'true'});
                    }
                );

        };

        authFac.logout = function() {
            destroyUserCredentials();
        };

        // authFac.register = function(registerData) {
        //
        //     $resource(baseURL + "users/register")
        //         .save(registerData,
        //             function(response) {
        //                 authFac.login({username:registerData.username, password:registerData.password});
        //                 if (registerData.rememberMe) {
        //                     $localStorage.storeObject('userinfo',
        //                         {username:registerData.username, password:registerData.password});
        //                 }
        //
        //                 $rootScope.$broadcast('registration:Successful');
        //             },
        //             function(response){
        //
        //                 var message = '\
        //         <div class="ngdialog-message">\
        //         <div><h3>Registration Unsuccessful</h3></div>' +
        //                     '<div><p>' +  response.data.err.message +
        //                     '</p><p>' + response.data.err.name + '</p></div>';
        //
        //                 ngDialog.openConfirm({ template: message, plain: 'true'});
        //
        //             }
        //
        //         );
        // };

        authFac.isAuthenticated = function() {
            return isAuthenticated;
        };

        authFac.middleware = function () {
            if (!isAuthenticated) {
                $state.go('app');
            }
        }

        authFac.getUsername = function() {
            return username;
        };

        loadUserCredentials();

        return authFac;

    }])

    .factory('UserAuthFactory', ['$resource', '$http', '$localStorage', '$rootScope', '$window', 'baseURL', 'ngDialog', '$state', function($resource, $http, $localStorage, $rootScope, $window, baseURL, ngDialog, $state){

        var authFac = {};
        var TOKEN_KEY = 'Token';
        var isAuthenticated = false;
        var username = '';
        var authToken = undefined;


        function loadUserCredentials() {
            var credentials = $localStorage.getObject(TOKEN_KEY,'{}');
            if (credentials.username != undefined) {
                useCredentials(credentials);
            }
        }

        function storeUserCredentials(credentials) {
            $localStorage.storeObject(TOKEN_KEY, credentials);
            useCredentials(credentials);
        }

        function useCredentials(credentials) {
            isAuthenticated = true;
            username = credentials.username;
            authToken = credentials.token;

            // Set the token as header for your requests!
            $http.defaults.headers.common['x-access-token'] = authToken;
        }

        function destroyUserCredentials() {
            authToken = undefined;
            username = '';
            isAuthenticated = false;
            $http.defaults.headers.common['x-access-token'] = authToken;
            $localStorage.remove(TOKEN_KEY);
        }

        authFac.login = function(loginData) {

            console.log(loginData);

            $resource(baseURL + "signin")
                .save(loginData,
                    function(response) {
                        if (response.type == 'fail') {
                            isAuthenticated = false;

                            var message = '\
                <div class="ngdialog-message">\
                <div><h3>登录失败</h3></div>' +
                                '<div><p>' +  '用户名或者账号错误' + '</p></div>' +
                                '<div class="ngdialog-buttons">\
                                    <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click=confirm("OK")>确定</button>\
                                </div>'

                            ngDialog.openConfirm({ template: message, plain: 'true'});
                        } else {
                            storeUserCredentials({username:loginData.username, token: response.token});
                            $rootScope.$broadcast('login:Successful');
                        }
                    },
                    function(response){
                        isAuthenticated = false;

                        var message = '\
                <div class="ngdialog-message">\
                <div><h3>登录失败</h3></div>' +
                            '<div><p>' +  '用户名或者账号错误' + '</p></div>' +
                            '<div class="ngdialog-buttons">\
                                <button type="button" class="ngdialog-button ngdialog-button-primary" ng-click=confirm("OK")>确定</button>\
                            </div>'

                        ngDialog.openConfirm({ template: message, plain: 'true'});
                    }
                );

        };

        authFac.logout = function() {
            destroyUserCredentials();
        };

        // authFac.register = function(registerData) {
        //
        //     $resource(baseURL + "users/register")
        //         .save(registerData,
        //             function(response) {
        //                 authFac.login({username:registerData.username, password:registerData.password});
        //                 if (registerData.rememberMe) {
        //                     $localStorage.storeObject('userinfo',
        //                         {username:registerData.username, password:registerData.password});
        //                 }
        //
        //                 $rootScope.$broadcast('registration:Successful');
        //             },
        //             function(response){
        //
        //                 var message = '\
        //         <div class="ngdialog-message">\
        //         <div><h3>Registration Unsuccessful</h3></div>' +
        //                     '<div><p>' +  response.data.err.message +
        //                     '</p><p>' + response.data.err.name + '</p></div>';
        //
        //                 ngDialog.openConfirm({ template: message, plain: 'true'});
        //
        //             }
        //
        //         );
        // };

        authFac.isAuthenticated = function() {
            return isAuthenticated;
        };

        authFac.middleware = function () {
            if (!isAuthenticated) {
                $state.go('app');
            }
        }

        authFac.getUsername = function() {
            return username;
        };

        loadUserCredentials();

        return authFac;

    }])
;
