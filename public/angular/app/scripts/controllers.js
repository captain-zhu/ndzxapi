/**
 * Created by zhu yongxuan on 2016/8/5.
 */

'use strict';

angular.module('ndzxApp')
    .constant("baseURL", "45.32.57.234/api/")

    // 登录控制器
    .controller('LoginController', ['$scope', 'ngDialog', '$localStorage', 'AuthFactory', '$state', function ($scope, ngDialog, $localStorage, AuthFactory, $state) {

        $scope.loginData = $localStorage.getObject('userinfo','{}');

        $scope.doLogin = function() {

            AuthFactory.login($scope.loginData);

            ngDialog.close();

        };

        $scope.openRegister = function () {
            ngDialog.open({ template: 'angular/app/views/admin/register.html', scope: $scope, className: 'ngdialog-theme-default', controller:"RegisterController" });
        };

    }])

    // 注册控制器
    .controller('RegisterController', ['$scope', 'ngDialog', '$localStorage', 'AuthFactory', function ($scope, ngDialog, $localStorage, AuthFactory) {

        $scope.register={};
        $scope.loginData={};

        $scope.doRegister = function() {
            console.log('Doing registration', $scope.registration);

            AuthFactory.register($scope.registration);

            ngDialog.close();

        };
    }])

    // 导航栏控制器
    .controller('HeaderController', ['$scope', '$state', '$rootScope', 'ngDialog', 'AuthFactory', function ($scope, $state, $rootScope, ngDialog, AuthFactory) {

        $scope.loggedIn = false;
        $scope.username = '';

        if(AuthFactory.isAuthenticated()) {
            $scope.loggedIn = true;
            $scope.username = AuthFactory.getUsername();
        }

        $scope.openLogin = function () {
            ngDialog.open({ template: 'angular/app/views/admin/login.html', scope: $scope, className: 'ngdialog-theme-default', controller:"LoginController" });
        };

        $scope.logOut = function() {
            AuthFactory.logout();
            $scope.loggedIn = false;
            $scope.username = '';
            $state.go('app');
        };

        $rootScope.$on('login:Successful', function () {
            $scope.loggedIn = AuthFactory.isAuthenticated();
            $scope.username = AuthFactory.getUsername();
            $state.go('admin');
        });

        $rootScope.$on('registration:Successful', function () {
            $scope.loggedIn = AuthFactory.isAuthenticated();
            $scope.username = AuthFactory.getUsername();
            $state.go('admin');
        });

        $scope.stateis = function(curstate) {
            return $state.is(curstate);
        };

    }])

    // 管理员首页
    .controller('AdminController', ['$scope', 'AuthFactory', function ($scope, AuthFactory) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
    }])

    // 用户管理页面
    .controller('AdminUsersController', ['$scope', 'AuthFactory', 'userFactory', 'ngDialog', '$controller', '$http', 'baseURL',
        function ($scope, AuthFactory, userFactory, ngDialog , $controller, $http, baseURL) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        //数据
        $scope.showUsers = false;
        $scope.message = "载入中 ...";
        $scope.users = undefined;
        // 获得用户资料
        userFactory.query().$promise.then(
            function (response) {
                $scope.users = response;
                $scope.showUsers = true;
            },
            function (response) {
                $scope.message = "错误: " + response.status + " " + response.statusText;
            }
        );
        // 获得分组信息
        $scope.groups = [];
        $http({
            method: "GET",
            url: baseURL + 'usergroups'
        }).then(function (response) {
            response.data.map(function (obj) {
                $scope.groups.push({
                    value : obj,
                    label : obj
                });
            });
            $scope.showUsers = true;
        }, function (response) {
            $scope.message = "错误：连接服务器失败";
        });

        // 修改用户资料
        $scope.editUserinfo = function (user) {
            ngDialog.open({
                template: 'angular/app/views/admin/edituserinfo.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminEditUserinfoController", {
                    $scope : $scope,
                    user : user
                })
            });
        }

        // 充值
        $scope.deposit = function (user) {
            ngDialog.open({
                template: 'angular/app/views/admin/deposit.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminDepositController", {
                    $scope : $scope,
                    user : user
                })
            });
        };

        // 订餐
        $scope.orderDinnerForUser = function (user) {
            ngDialog.open({
                template: 'angular/app/views/admin/orderdinner.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminOrderDinnerController", {
                    $scope : $scope,
                    user : user
                })
            });
        };
        
        // 给全组充值
        $scope.depositForGroup = function () {
            ngDialog.open({
                template: 'angular/app/views/admin/groupdeposit.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminGroupDepositController", {
                    $scope : $scope
                })
            });
        }

    }])

    // 修改用户资料的控制器
    .controller('AdminEditUserinfoController', ['$scope', 'AuthFactory', 'userFactory', 'ngDialog', 'user', function ($scope, AuthFactory, userFactory, ngDialog, user) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.user = user;
        $scope.showUserinfo = true;
        $scope.userinfomessage = "";
        // 修改用户资料
        $scope.doEdit = function() {
            // 更新用户信息
            userFactory.update({
                id : user.id
            }, {
                name : user.userinfo.name,
                telephone : user.userinfo.telephone,
                privilege : user.privilege
            }).$promise.then(function (response) {
                if (response.type == 'fail') {
                    $scope.showUserinfo = false;
                    $scope.userinfomessage = response.msg;
                } else {
                    ngDialog.close();
                }
            }, function (response) {
                $scope.showUserinfo = false;
                $scope.userinfomessage = "未能连接到服务器";
            });
        };
        
    }])

    // 为单个用户充值的控制器
    .controller('AdminDepositController', ['$scope', 'AuthFactory', 'depositFactory', 'ngDialog', 'user', function ($scope, AuthFactory, depositFactory, ngDialog, user) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.user = user;
        $scope.depositorder = {
            value : 0
        };
        $scope.showDeposit = true;
        $scope.depositMessage = "";
        // 修改用户资料
        $scope.doDeposit = function() {
            // 更新用户信息
            depositFactory.save({}, {
                user_id : user.id,
                value : $scope.depositorder.value,
                type : 0,
                adminname: AuthFactory.getUsername()
            }).$promise.then(function (response) {
                if (response.type == 'fail') {
                    $scope.showDeposit = false;
                    $scope.depositMessage = response.msg;
                } else {
                    user.gold = (Number(user.gold)
                        + Number($scope.depositorder.value)).toFixed(2);
                    ngDialog.close();
                }
            }, function (response) {
                $scope.showDeposit = false;
                $scope.depositMessage = "错误: 连接不上服务器";
            });
        };

    }])

    // 为全组充值的控制器
    .controller('AdminGroupDepositController', ['$scope', 'AuthFactory', 'depositFactory', 'ngDialog', '$http', 'baseURL',
        function ($scope, AuthFactory, depositFactory, ngDialog, $http, baseURL) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.group = {
            name : ""
        };
        $scope.showDepositGroup = true;
        $scope.showDepositGroupError = false;
        $scope.groupDepositMessage = "载入中...";
        $scope.groupOrder = {};
        // 为全组充值
        $scope.doDepositForGroup = function() {
            $http({
                method: 'POST',
                url: baseURL + 'depositforgroups',
                data: {
                    privilege : $scope.groupOrder.mygroup,
                    value: $scope.groupOrder.value,
                    adminname: AuthFactory.getUsername()
                }
            }).then(function (response) {
                if (response.type == 'fail') {
                    $scope.showDepositGroupError = true;
                    $scope.groupDepositMessage = response.msg;
                } else {
                    ngDialog.close();
                }
            }, function (response) {
                $scope.showDepositGroupError = true;
                $scope.groupDepositMessage = "错误:未能连接到服务器";
            });
        };
    }])

    // 为用户订餐
    .controller('AdminOrderDinnerController', ['$scope', 'AuthFactory', 'orderFactory', 'menuFactory','ngDialog', 'user', '$http', 'baseURL',
        function ($scope, AuthFactory, orderFactory, menuFactory, ngDialog, user, $http, baseURL) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.user = user;
        $scope.showOrder = false;
        $scope.showOrderError = true;
        $scope.orderMessage = "载入中...";
        $scope.orderData = {
            breakfast : '',
            lunch : '',
            dinner : '',
            date : ''
        };
        $scope.orderInfos = {};
        // 获得菜单
        menuFactory.get().$promise.then(
            function (response) {
                $scope.menus = response;
            }, function (response) {
                $scope.orderMessage = "错误：从服务器获取数据失败";
            });
        // 获得订餐的时间
        $http({
            method: "GET",
        }).then(function (response) {
            url: baseURL + 'dinnerdate'
            $scope.orderData.date = response.data.date;
            // 获得用户现在的订餐信息
            $http({
                method: "PATCH",
                url: baseURL + 'orderinfos',
                data : {
                    user_id : user.id,
                    date : $scope.orderData.date
                }
            }).then(
                function (response) {
                    $scope.showOrder = true;
                    $scope.showOrderError = false;
                    $scope.orderInfos = response.data;
                }, function (response) {
                    $scope.orderMessage = "错误：从服务器获取数据失败";
                });
        }, function (response) {
            $scope.orderMessage = "错误：连接服务器失败";
        });

        // 订早餐
        $scope.orderBreakfast = function() {
            // 保存订餐信息
            orderFactory.save({}, {
                'user_id' : user.id,
                'menu_id' : $scope.menus[0].id,
                'single_value' : $scope.menus[0].value,
                'count' : $scope.orderData.breakfast,
                'order' : 1,
                'date' : $scope.orderData.date
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.showOrderError = true;
                        $scope.orderMessage = response.msg;
                    } else {
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.showOrderError = true;
                    $scope.orderMessage = "错误： 连接服务器失败"
                }
            );
        };

        // 订午餐
        $scope.orderLunch = function () {
            orderFactory.save({}, {
                'user_id' : user.id,
                'menu_id' : $scope.menus[1].id,
                'single_value' : $scope.menus[1].value,
                'count' : $scope.orderData.lunch,
                'order' : 2,
                'date' : $scope.orderData.date
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.showOrderError = true;
                        $scope.orderMessage = response.msg;
                    } else {
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.showOrderError = true;
                    $scope.orderMessage = "错误： 连接服务器失败"
                }
            );
        };

        // 订晚餐
        $scope.orderDinner = function () {
            orderFactory.save({}, {
                'user_id' : user.id,
                'menu_id' : $scope.menus[2].id,
                'single_value' : $scope.menus[2].value,
                'count' : $scope.orderData.dinner,
                'order' : 3,
                'date' : $scope.orderData.date
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.showOrderError = true;
                        $scope.orderMessage = response.msg;
                    } else {
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.showOrderError = true;
                    $scope.orderMessage = "错误： 连接服务器失败"
                }
            );
        };

    }])
    
    // 订单管理控制器
    .controller('AdminOrdersController', ['$scope', 'orderFactory', 'AuthFactory', function ($scope, orderFactory, AuthFactory) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        //数据
        $scope.showOrders = false;
        $scope.message = "载入中 ...";
        $scope.orderOrders = [
            {
                'label' : '早餐',
                'value' : '1'
            },{
                'label' : '午餐',
                'value' : '2'
            },{
                'label' : '晚餐',
                'value' : '3'
            }
        ];
        $scope.search = {};
        $scope.dt = {
            date : function (newDate) {
                if (newDate != undefined) {
                    var date = new Date(Date.parse(newDate));
                    $scope.search.date = date.getFullYear() + '-' + ('0' + (date.getUTCMonth() + 1)).slice(-2) + '-' + date.getDate();
                }
                return newDate;
            }
        };
        $scope.popup = {};
        $scope.orders = {};
        // 获得用户资料
        orderFactory.query().$promise.then(
            function (response) {
                $scope.orders = response;
                $scope.showOrders = true;
            },
            function (response) {
                $scope.message = "错误: " + response.status + " " + response.statusText;
            }
        );

        $scope.today = function() {
            $scope.dt.date = new Date();
        };


        $scope.clear = function() {
            $scope.dt.date = null;
            $scope.search.date = null;
        };

        $scope.options = {
            customClass: getDayClass
        };

        // Disable weekend selection
        // function disabled(data) {
        //     var date = data.date,
        //         mode = data.mode;
        //     return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
        // }

        $scope.setDate = function(year, month, day) {
            $scope.dt.date = new Date(year, month, day);
        };

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var afterTomorrow = new Date(tomorrow);
        afterTomorrow.setDate(tomorrow.getDate() + 1);
        $scope.events = [
            {
                date: tomorrow,
                status: 'full'
            },
            {
                date: afterTomorrow,
                status: 'partially'
            }
        ];

        function getDayClass(data) {
            var date = data.date,
                mode = data.mode;
            if (mode === 'day') {
                var dayToCheck = new Date(date).setHours(0,0,0,0);

                for (var i = 0; i < $scope.events.length; i++) {
                    var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

                    if (dayToCheck === currentDay) {
                        return $scope.events[i].status;
                    }
                }
            }

            return '';
        };

        $scope.open = function() {
            $scope.popup.opened = true;
        };
    }])

    // 充值管理控制器
    .controller('AdminDepositsController', ['$scope', 'depositFactory', 'AuthFactory', function ($scope, depositFactory, AuthFactory) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        //数据
        $scope.showDeposits = false;
        $scope.message = "载入中 ...";
        $scope.dipositMethods = [
            {
                'label' : '管理员充值',
                'value' : '0'
            },{
                'label' : '微信充值',
                'value' : '1'
            }
        ];
        $scope.search = {};
        $scope.searchDate = {};
        $scope.dt = {
            date : function (newDate) {
                if (newDate != undefined) {
                    var date = new Date(Date.parse(newDate));
                    $scope.searchDate.date = date.getFullYear() + '-' + ('0' + (date.getUTCMonth() + 1)).slice(-2) + '-' + date.getDate();
                }
                return newDate;
            }
        };
        $scope.popup = {};
        $scope.deposits = {};
        // 获得用户资料
        depositFactory.query().$promise.then(
            function (response) {
                $scope.deposits = response;
                $scope.showDeposits = true;
            },
            function (response) {
                $scope.message = "错误: " + response.status + " " + response.statusText;
            }
        );

        $scope.today = function() {
            $scope.dt.date = new Date();
        };


        $scope.clear = function() {
            $scope.dt.date = null;
            $scope.search.created_at = null;
        };

        $scope.options = {
            customClass: getDayClass
        };

        // Disable weekend selection
        // function disabled(data) {
        //     var date = data.date,
        //         mode = data.mode;
        //     return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
        // }

        $scope.setDate = function(year, month, day) {
            $scope.dt.date = new Date(year, month, day);
        };

        var tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        var afterTomorrow = new Date(tomorrow);
        afterTomorrow.setDate(tomorrow.getDate() + 1);
        $scope.events = [
            {
                date: tomorrow,
                status: 'full'
            },
            {
                date: afterTomorrow,
                status: 'partially'
            }
        ];

        function getDayClass(data) {
            var date = data.date,
                mode = data.mode;
            if (mode === 'day') {
                var dayToCheck = new Date(date).setHours(0,0,0,0);

                for (var i = 0; i < $scope.events.length; i++) {
                    var currentDay = new Date($scope.events[i].date).setHours(0,0,0,0);

                    if (dayToCheck === currentDay) {
                        return $scope.events[i].status;
                    }
                }
            }

            return '';
        };

        $scope.open = function() {
            $scope.popup.opened = true;
        };
    }])
    
    // 管理菜单的控制器
    .controller('AdminMenusController', ['$scope', 'menuFactory', 'dinnerFactory', 'dishFactory', 'AuthFactory', 'ngDialog', '$controller', '$state', '$rootScope',function ($scope, menuFactory, dinnerFactory, dishFactory, AuthFactory, ngDialog, $controller, $state, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        //数据
        $scope.showMenus = false;
        $scope.errorMsgForMenus = "载入中 ...";
        $scope.showBreakfastError = false;
        $scope.breakfastError = "";
        $scope.showLunchError = false;
        $scope.lunchError = "";
        $scope.showDinnerError = false;
        $scope.dinnerError = "";
        $scope.breakfast = {};
        $scope.lunch = {};
        $scope.dinner = {};
        // 获得menu数据
        menuFactory.get().$promise.then(
            function (response) {
                $scope.breakfast = response[0];
                $scope.lunch = response[1];
                $scope.dinner = response[2];
                $scope.showMenus = true;
            }, function (response) {
                $scope.errorMsgForMenus = "错误：从服务器获取数据失败";
            });
        // accordion设定
        $scope.status = {
            isBreakfastCustomHeaderOpen: [false],
            isLunchCustomHeaderOpen: [false],
            isDinnerCustomHeaderOpen: [false]
        };

        // 聆听更新状态
        $rootScope.$on('AdminMenus:fail', function () {
            $state.reload();
        });
        // 修改价格
        $scope.changeValue = function (type, value) {
            ngDialog.open({
                template: 'angular/app/views/admin/changevalue.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminChangeValueController", {
                    $scope : $scope,
                    type : type,
                    value : value
                })
            });
        };
        // 开关订单状态
        $scope.toggleState = function (type, state) {
            if (state == 0) {
                switch (type) {
                    case 1:
                        dinnerFactory.update({
                            id : 1
                        }, {
                            state : 1
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showBreakfastError = true;
                                    $scope.breakfastError = response.msg;
                                } else {
                                    $scope.showBreakfastError = false;
                                    $scope.breakfast.state = 1;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showBreakfastError = true;
                                $scope.breakfastError = "服务器出错";
                            }
                        );
                        break;
                    case 2:
                        dinnerFactory.update({
                            id : 2
                        }, {
                            state : 1
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showLunchError = true;
                                    $scope.lunchError = response.msg;
                                } else {
                                    $scope.showLunchError = false;
                                    $scope.lunch.state = 1;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showLunchError = true;
                                $scope.lunchError = "服务器出错";
                            }
                        );
                        break;
                    case 3:
                        dinnerFactory.update({
                            id : 3
                        }, {
                            state : 1
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showDinnerError = true;
                                    $scope.dinnerError = response.msg;
                                } else {
                                    $scope.showDinnerError = false;
                                    $scope.dinner.state = 1;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showDinnerError = true;
                                $scope.dinnerError = "服务器出错";
                            }
                        );
                        break;
                    default:
                        break;
                }
            } else {
                switch (type) {
                    case 1:
                        dinnerFactory.update({
                            id : 1
                        }, {
                            state : 0
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showBreakfastError = true;
                                    $scope.breakfastError = response.msg;
                                } else {
                                    $scope.showBreakfastError = false;
                                    $scope.breakfast.state = 0;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showBreakfastError = true;
                                $scope.breakfastError = "服务器出错";
                            }
                        );
                        break;
                    case 2:
                        dinnerFactory.update({
                            id : 2
                        }, {
                            state : 0
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showLunchError = true;
                                    $scope.lunchError = response.msg;
                                } else {
                                    $scope.showLunchError = false;
                                    $scope.lunch.state = 0;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showLunchError = true;
                                $scope.lunchError = "服务器出错";
                            }
                        );
                        break;
                    case 3:
                        dinnerFactory.update({
                            id : 3
                        }, {
                            state : 0
                        }).$promise.then(
                            function (response) {
                                if (response.type == 'fail') {
                                    $scope.showDinnerError = true;
                                    $scope.dinnerError = response.msg;
                                } else {
                                    $scope.showDinnerError = false;
                                    $scope.dinner.state = 0;
                                    $state.reload();
                                }
                            }, function (response) {
                                $scope.showDinnerError = true;
                                $scope.dinnerError = "服务器出错";
                            }
                        );
                        break;
                    default:
                        break;
                }
            }
        };

        // 修改订单开始时间
        $scope.changeStartTime = function (type) {
            ngDialog.open({
                template: 'angular/app/views/admin/changestarttime.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminChangeStartTimeController", {
                    $scope : $scope,
                    type : type
                })
            });
        };

        // 修改订单开始时间
        $scope.changeEndTime = function (type) {
            ngDialog.open({
                template: 'angular/app/views/admin/changeendtime.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminChangeEndTimeController", {
                    $scope : $scope,
                    type : type
                })
            });
        };

        // 删除菜品
        $scope.deleteDish = function (id) {
            ngDialog.open({
                template: 'angular/app/views/admin/deletedish.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminDeleteDishController", {
                    $scope : $scope,
                    id : id
                })
            });
        };
        
        // 修改菜品
        $scope.editDish = function (dish, type) {
            ngDialog.open({
                template: 'angular/app/views/admin/editdish.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminEditDishController", {
                    $scope : $scope,
                    dish : dish,
                    type : type
                })
            });
        };

        // 新建菜品
        $scope.newDish = function(type) {
            ngDialog.open({
                template: 'angular/app/views/admin/newdish.html',
                scope: $scope,
                className: 'ngdialog-theme-default',
                controller: $controller("AdminNewDishController", {
                    $scope : $scope,
                    type : type
                })
            });
        }
    }])

    // 改变订单价格的的控制器
    .controller('AdminChangeValueController', ['$scope', 'AuthFactory', 'menuFactory', 'ngDialog', 'type', 'value', '$rootScope', function ($scope, AuthFactory, menuFactory, ngDialog, type, value, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.changeType = type;
        $scope.show = {
            showChangeValueError : false,
            changeValueError : ""
        };
        $scope.originalValue = value;
        // 改变价格
        $scope.doChangeValue = function () {
            if ($scope.changeType == 1) {
                value = $scope.breakfast.value;
            } else if ($scope.changeType == 2) {
                value = $scope.lunch.value;
            } else if ($scope.changeType == 3) {
                value = $scope.dinner.value;
            }
            menuFactory.update({
                id : type
            }, {
                value : value
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showChangeValueError = true;
                        $scope.show.changeValueError = response.msg;
                        $rootScope.$broadcast('AdminMenus:fail');
                    } else {
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showChangeValueError = true;
                    $scope.show.changeValueError = "错误：未能连接到服务器";
                    $rootScope.$broadcast('AdminMenus:fail');
                }
            );
        }

    }])

    // 改变餐次开始时间的的控制器
    .controller('AdminChangeStartTimeController', ['$scope', 'AuthFactory', 'dinnerFactory', 'ngDialog', 'type', '$rootScope', function ($scope, AuthFactory, dinnerFactory, ngDialog, type, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.show = {
            showChangeStartTimeError : false,
            changeStartTimeError : ""
        };
        $scope.startTimeData = {};
        // 改变价格
        $scope.doChangeStartTime = function () {
            var time = $scope.startTimeData.time;
            var newDate = time.toTimeString().split(' ')[0];
            dinnerFactory.update({
                id : type
            }, {
                start_time : newDate
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showChangeStartTimeError = true;
                        $scope.show.changeStartTimeError = response.msg;
                    } else {
                        $rootScope.$broadcast('AdminMenus:fail');
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showChangeStartTimeError = true;
                    $scope.show.changeStartTimeError = "错误：未能连接到服务器";
                }
            );
        }
    }])

    // 改变餐次结束时间的的控制器
    .controller('AdminChangeEndTimeController', ['$scope', 'AuthFactory', 'dinnerFactory', 'ngDialog', 'type', '$rootScope', function ($scope, AuthFactory, dinnerFactory, ngDialog, type, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.show = {
            showChangeEndTimeError : false,
            changeEndTimeError : ""
        };
        $scope.endTimeData = {};
        // 改变价格
        $scope.doChangeEndTime = function () {
            var time = $scope.endTimeData.time;
            var newDate = time.toTimeString().split(' ')[0];
            dinnerFactory.update({
                id : type
            }, {
                end_time : newDate
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showChangeEndTimeError = true;
                        $scope.show.changeEndTimeError = response.msg;
                    } else {
                        $rootScope.$broadcast('AdminMenus:fail');
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showChangeEndTimeError = true;
                    $scope.show.changeEndTimeError = "错误：未能连接到服务器";
                }
            );
        }
    }])

    // 新建菜品的的控制器
    .controller('AdminNewDishController', ['$scope', 'AuthFactory', 'dishFactory', 'ngDialog', 'type', '$rootScope', function ($scope, AuthFactory, dishFactory, ngDialog, type, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.newingDish = {};
        $scope.show = {
            showNewDishError : false,
            newDishError : ""
        };
        $scope.degreeData = [
            {
                label : '一星',
                value : 1
            }, {
                label : '二星',
                value : 2
            }, {
                label : '三星',
                value : 3
            }, {
                label : '四星',
                value : 4
            }, {
                label : '五星',
                value : 5
            }
        ];
        // 改变价格
        $scope.doNewDish = function () {
            dishFactory.save({}, {
                name : $scope.newingDish.name,
                peppery_degree : $scope.newingDish.peppery_degree,
                description : $scope.newingDish.description,
                menu_id : type
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showNewDishError = true;
                        $scope.show.newDishError = response.msg;
                        $rootScope.$broadcast('AdminMenus:fail');
                    } else {
                        $rootScope.$broadcast('AdminMenus:fail');
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showNewDishError = true;
                    $scope.show.newDishError = "错误：未能连接到服务器";
                    $rootScope.$broadcast('AdminMenus:fail');
                }
            );
        };

    }])

    // 删除菜品的的控制器
    .controller('AdminDeleteDishController', ['$scope', 'AuthFactory', 'dishFactory', 'ngDialog', 'id', '$rootScope', function ($scope, AuthFactory, dishFactory, ngDialog, id, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.show = {
            showDeleteDishError : false,
            deleteDishError : ""
        };
        // 删除菜品
        $scope.doDeleteDish = function () {
            dishFactory.delete({
                id : id
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showDeleteDishError = true;
                        $scope.show.deleteDishError = response.msg;
                        $rootScope.$broadcast('AdminMenus:fail');
                    } else if (response.type == 'success') {
                        $rootScope.$broadcast('AdminMenus:fail');
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showDeleteDishError = true;
                    $scope.show.deleteDishError = "错误：未能连接到服务器";
                    $rootScope.$broadcast('AdminMenus:fail');
                }
            );
        };

    }])

    // 编辑菜品的的控制器
    .controller('AdminEditDishController', ['$scope', 'AuthFactory', 'dishFactory', 'ngDialog', 'type', 'dish', '$rootScope', function ($scope, AuthFactory, dishFactory, ngDialog, type, dish, $rootScope) {
        // 判断是否已经登录，没有登录不让其使用本页
        AuthFactory.middleware();
        // 数据
        $scope.editingDish = dish;
        $scope.show = {
            showEditDishError : false,
            editDishError : ""
        };
        $scope.degreeData = [
            {
                label : '一星',
                value : 1
            }, {
                label : '二星',
                value : 2
            }, {
                label : '三星',
                value : 3
            }, {
                label : '四星',
                value : 4
            }, {
                label : '五星',
                value : 5
            }
        ];
        // 编辑菜品
        $scope.doEditDish = function () {
            dishFactory.update({
                id : dish.id
            }, {
                name : $scope.editingDish.name,
                peppery_degree : $scope.editingDish.peppery_degree,
                description : $scope.editingDish.description
            }).$promise.then(
                function (response) {
                    if (response.type == 'fail') {
                        $scope.show.showEditDishError = true;
                        $scope.show.editDishError = response.msg;
                        $rootScope.$broadcast('AdminMenus:fail');
                    } else {
                        dish = $scope.editingDish;
                        ngDialog.close();
                    }
                }, function (response) {
                    $scope.show.showEditDishError = true;
                    $scope.show.editDishError = "错误：未能连接到服务器";
                    $rootScope.$broadcast('AdminMenus:fail');
                }
            );
        };

    }])
;
