const mainApp = angular.module('BlankApp', ['ngMaterial', 'ngSanitize' ]);

mainApp.controller('leftSideBar', [
    '$scope', '$http',
    function($scope, $http){
        $scope.navStatus = false;
        $scope.roleName = function(name){
          console.log(name)
          $http.get('/js/ru.json').then(function (data){
            $scope.role = data.data[name];
          })
        }
    }
])



mainApp.controller('client', [
    '$scope', '$timeout', '$mdSidenav', '$log', '$mdDialog', '$http',
    function($scope, $timeout, $mdSidenav, $log, $mdDialog, $http){
      //  setTimeout(function(){
          $scope.loaded = true;
      //  },1000)
      $scope.OpenModalClientsCreate = function(ev) {
            $mdDialog.show({
              controller: OpenModalClientsCreate,
              templateUrl: '/clients/create',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: $scope.customFullscreen
            })
            .then(function(answer) {
              $scope.status = 'You said the information was "' + answer + '".';
            }, function() {
              $scope.status = 'You cancelled the dialog.';
            });
          };
    }])

mainApp.controller('orders', [
    '$scope', '$timeout', '$mdSidenav', '$log',
    function($scope, $timeout, $mdSidenav, $log){
        $scope.loaded = true;

        $scope.toggleRight = buildToggler('right');
        $scope.isOpenRight = function(){
          return $mdSidenav('right').isOpen();
        };
        function buildDelayedToggler(navID) {
          return debounce(function() {
            $mdSidenav(navID)
              .toggle()
              .then(function () {
                $log.debug("toggle " + navID + " is done");
              });
          }, 200);
        };
        function buildToggler(navID) {
          return function() {
            $mdSidenav(navID)
              .toggle()
              .then(function () {
                $log.debug("toggle " + navID + " is done");
              });
          };
        }
        function debounce(func, wait, context) {
           var timer;
           return function debounced() {
             var context = $scope,
                 args = Array.prototype.slice.call(arguments);
             $timeout.cancel(timer);
             timer = $timeout(function() {
               timer = undefined;
               func.apply(context, args);
             }, wait || 10);
           };
         }
    }
])

mainApp.controller('RightCtrl', function ($scope, $timeout, $mdSidenav, $log) {
  $scope.myDate;
  $scope.changedate = function(){
      var now = $scope.myDate;
      var formated_date = now.format("yyyy-mm-dd");
      $scope.created_from =  formated_date;
  }
  // duble
  $scope.ChangeChechbox = function (){
    for(let item in $scope.defaultStatusOrder){
        document.getElementById($scope.defaultStatusOrder[item]).checked=false;
      }
    for(let item in $scope.selectedVegetables){
        document.getElementById($scope.selectedVegetables[item]).checked=true;
      }
  }
  // end duble
    $scope.close = function () {

      $mdSidenav('right').close()
        .then(function () {
          $log.debug("close RIGHT is done");
        });
    };
})
mainApp.controller('specification', [
    '$scope', '$timeout', '$mdSidenav', '$log', '$mdDialog', '$http',
    function($scope, $timeout, $mdSidenav, $log, $mdDialog, $http){
          $scope.loaded = true;
//Вызов модалок
          $scope.OpenModalCreate = function(ev) {
            $mdDialog.show({
              controller: OpenModalCreateCtrl,
              templateUrl: '/specifications/create',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: $scope.customFullscreen
            })
            .then(function(answer) {
              $scope.status = 'You said the information was "' + answer + '".';
            }, function() {
              $scope.status = 'You cancelled the dialog.';
            });
          };

          $scope.OpenModalShow = function(ev, id) {
            $mdDialog.show({
              controller: OpenModalCreateCtrl,
              templateUrl: '/specifications/'+id,
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: $scope.customFullscreen
            })
            .then(function(answer) {
              $scope.status = 'You said the information was "' + answer + '".';
            }, function() {
              $scope.status = 'You cancelled the dialog.';
            });
          };


    }])
mainApp.controller('cost-item', [
    '$scope', '$timeout', '$mdSidenav', '$log', '$mdDialog', '$http',
    function($scope, $timeout, $mdSidenav, $log, $mdDialog, $http){
          $scope.loaded = true;
//Вызов модалок
          $scope.CostItemsCreatess = function(ev) {
            $mdDialog.show({
              controller: CostItemsCreatess,
              templateUrl: '/cost_items/create',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: $scope.customFullscreen
            })
            .then(function(answer) {
              $scope.status = 'You said the information was "' + answer + '".';
            }, function() {
              $scope.status = 'You cancelled the dialog.';
            });
          };
    }])
mainApp.controller('user', [
    '$scope', '$timeout', '$mdSidenav', '$log', '$mdDialog', '$http',
    function($scope, $timeout, $mdSidenav, $log, $mdDialog, $http){
          $scope.loaded = true;

          $scope.OpenModalUserCreate = function(ev) {
            $mdDialog.show({
              controller: OpenModalUserCreate,
              templateUrl: '/users/create',
              parent: angular.element(document.body),
              targetEvent: ev,
              clickOutsideToClose:true,
              fullscreen: $scope.customFullscreen
            })
            .then(function(answer) {
              $scope.status = 'You said the information was "' + answer + '".';
            }, function() {
              $scope.status = 'You cancelled the dialog.';
            });
          };
    }])
    // Контроллеры
    function OpenModalCreateCtrl($scope, $mdDialog, $http) {
    $scope.title = 'Создание спецификации'
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
    function OpenModalShow($scope, $mdDialog, $http) {
    $scope.title = 'Спецификация'
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
    function OpenModalUserCreate($scope, $mdDialog, $http) {
    $scope.title = 'Создание пользователя'
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
    function OpenModalClientsCreate($scope, $mdDialog, $http) {
    $scope.title = 'Создание клиента';
$scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
    function CostItemsCreatess($scope, $mdDialog, $http) {
    $scope.title = 'Создание статьи затрат';
    $scope.hide = function() {
      $mdDialog.hide();
    };

    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
