/**
 * Created by zhu yongxuan on 2016/8/5.
 */

'use strict';

angular.module('ndzxApp', ['ngAnimate', 'ngTouch', 'ui.bootstrap','ui.router','ngResource','ngDialog', 'ndzxApp.filters'])
    .config(function($stateProvider, $urlRouterProvider) {
        $stateProvider

        // 首页
            .state('app', {
                url:'/',
                views: {
                    'header': {
                        templateUrl : 'angular/app/views/header.html',
                    },
                    'content': {
                        templateUrl : 'angular/app/views/home.html',
                        controller : 'HeaderController'
                    },
                    'footer': {
                        templateUrl : 'angular/app/views/footer.html',
                    }
                }
            })

        // 管理系统路径
            .state('admin', {
                url:'/admin',
                views: {
                    'header': {
                        templateUrl : 'angular/app/views/admin/header.html',
                        controller  : 'HeaderController'
                    },
                    'content': {
                        templateUrl : 'angular/app/views/admin/home.html',
                        controller : 'AdminController'
                    },
                    'footer': {
                        templateUrl : 'angular/app/views/admin/footer.html',
                    }
                }

            })

            // 管理员的用户管理页面
            .state('admin.users', {
                url:'/users',
                views: {
                    'content@': {
                        templateUrl : 'angular/app/views/admin/user.html',
                        controller  : 'AdminUsersController'
                    }
                }
            })

            // route for the contactus page
            .state('admin.menus', {
                url:'/menus',
                views: {
                    'content@': {
                        templateUrl : 'angular/app/views/admin/menus.html',
                        controller  : 'AdminMenusController'
                    }
                }
            })

            // 订单管理页
            .state('admin.orders', {
                url: '/orders',
                views: {
                    'content@': {
                        templateUrl : 'angular/app/views/admin/orders.html',
                        controller  : 'AdminOrdersController'
                    }
                }
            })

            // 订单管理页
            .state('admin.deposits', {
                url: '/deposits',
                views: {
                    'content@': {
                        templateUrl : 'angular/app/views/admin/deposits.html',
                        controller  : 'AdminDepositsController'
                    }
                }
            })

        $urlRouterProvider.otherwise('/');
    })
;
