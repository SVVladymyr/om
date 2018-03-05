const mainApp = angular.module('BlankApp', ['ngMaterial', 'ngSanitize' ]);

mainApp.controller('leftSideBar', [
    '$scope',
    function($scope){
        $scope.navStatus = false;
    }
])



mainApp.controller('client', [
    '$scope', '$timeout', '$mdSidenav', '$log',
    function($scope, $timeout, $mdSidenav, $log){
      //  setTimeout(function(){
          $scope.loaded = true;
      //  },1000)
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
    }])
mainApp.controller('user', [
    '$scope', '$timeout', '$mdSidenav', '$log',
    function($scope, $timeout, $mdSidenav, $log){
          $scope.loaded = true;
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
