const mainApp = angular.module('BlankApp', ['ngMaterial' ]);

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
