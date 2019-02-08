const mainApp = angular.module('BlankApp', ['ngMaterial', 'ngSanitize' ]);

mainApp.controller('leftSideBar', [
    '$scope', '$http', '$mdToast',
    function($scope, $http, $mdToast){
        $scope.navStatus = false;
        $scope.roleName = function(name){
          $http.get('/js/ru.json').then(function (data){
            $scope.role = data.data[name];
          })
        }
        $scope.showCustomToast = function(message) {
            $mdToast.show({
                locals: {
                    theScope: {
                        message: message
                    }
                },
                hideDelay   : 6000,
                position    : 'top right',
                controller  : 'ToastCtrl',
                templateUrl : '/dialog/error.html'
            });
        };
    }
])

mainApp.controller('ToastCtrl', function($scope, $mdToast, $mdDialog, theScope, $http) {
    $http.get('/js/ru.json')
        .then(function (data) {
            let translateResponse = data.data[theScope.message];
            translateResponse != undefined ? $scope.data =  translateResponse : $scope.data = theScope.message;
        })

    $scope.closeToast = function() {
        $mdToast
            .hide()
            .then(function() {
                isDlgOpen = false;
            });
    };

});

mainApp.controller('limits', [
    '$scope', '$timeout', '$mdSidenav', '$log', '$mdDialog', '$http',
    function($scope, $timeout, $mdSidenav, $log, $mdDialog, $http) {
        //  setTimeout(function(){
        $scope.loaded = true;
        $scope.SetLimits = function(ev, id) {
            $mdDialog.show({
                controller: SetLimit,
                templateUrl: '/clients/'+id+'/limits/set',
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

          $scope.SetLimits = function(ev, id) {
            $mdDialog.show({
              controller: SetLimit,
              templateUrl: '/clients/'+id+'/limits/set',
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
mainApp.controller('homeCtrl', function ($scope, $timeout, $mdSidenav, $log) {
    $scope.from;
    $scope.to;
    $scope.loaded = true;
    $scope.changedate = function() {

        let from = $scope.from;
        if(!!from){
            let fromformated_date = from.format("yyyy-mm-dd");
            $scope.fromL =  fromformated_date;
        }

        let to = $scope.to;
        if(!!to){
            let toformated_date = to.format("yyyy-mm-dd");
            $scope.toL =  toformated_date;
        }
    }
})


mainApp.controller('RightUserCtrl', function ($scope, $timeout, $mdSidenav, $log, $element) {
    $scope.clearUsersFilter = function () {
        $(':input','#filter_users')
            .not(':button, :submit, :reset, :hidden')
            .val('')
            .removeAttr('checked')
            .removeAttr('selected');
            $scope.selectedRoles = null;
    }
    $scope.Roles = [
        {name: 'Администратор компании', val: 'company_admin'}, {name: 'Администратор клиента', val: 'client_admin'},  {name: 'Подуровень', val: 'sublevel'}, {name: 'Менеджер', val: 'manager'}, {name: 'Заказчик', val: 'consumer'}
    ]
    $scope.ChangeChechbox = function (){
        for(let item in $scope.selectedRoles){
            console.log($scope.selectedVegetablesClient[item])
        }
    }
})
mainApp.controller('RightCtrl', function ($scope, $timeout, $mdSidenav, $log, $element, $mdDateLocale, $filter) {
    $scope.dateState = function(q, w){
        if(q == 'created_from') $scope.createdFrom = new Date(w);
        if(q == 'expected_delivery_from') $scope.expectedDeliveryFrom = new Date(w);
        if(q == 'created_to') $scope.createdToOrd = new Date(w);
        if(q == 'expected_delivery_to') $scope.expectedDeliveryToOrd = new Date(w);
    }
    $mdDateLocale.formatDate = function(date, timezone) {
        if (!date) {
            return '';
        }

        var localeTime = date.toLocaleTimeString();
        var formatDate = date;
        if (date.getHours() === 0 &&
            (localeTime.indexOf('11:') !== -1 || localeTime.indexOf('23:') !== -1)) {
            formatDate = new Date(date.getFullYear(), date.getMonth(), date.getDate(), 1, 0, 0);
        }

        return $filter('date')(formatDate, 'd/M/yyyy', timezone);
    }
    $scope.myDate;
    $scope.clearFilter = function () {

        $scope.createdFrom = null;
        $scope.createdToOrd = null;
        $scope.expectedDeliveryFrom = null;
        $scope.createdToDel = null;
        $scope.expectedDeliveryToDel = null;
        $scope.expectedDeliveryToOrd = null;
        $scope.changedate();
        $scope.defaultStatusOrder = null;
        $scope.selectedVegetables = null;
        $scope.defaultClientOrder = null;
        $scope.selectedVegetablesClient = null;
        for(var i =0; i < document.querySelectorAll('input').length; i++){
            document.querySelectorAll('input')[i].checked = false;
        }
        $scope.ChangeChechbox();

    }



    $scope.clients = [];

    $scope.changedate = function(){
      let createdFrom = $scope.createdFrom;
      if(!!createdFrom){
          console.log(createdFrom)
        let createdFromformated_date = createdFrom.format("yyyy-mm-dd");
        $scope.created_from =  createdFromformated_date;
      }
      let expectedDeliveryFrom = $scope.expectedDeliveryFrom;
      if(!!expectedDeliveryFrom){
        let expectedDeliveryFromformated_date = expectedDeliveryFrom.format("yyyy-mm-dd");
        $scope.expected_delivery_from =  expectedDeliveryFromformated_date;
      }
      let createdToDel = $scope.createdToDel;
      if(!!createdToDel){
        let createdToDelformated_date = createdToDel.format("yyyy-mm-dd");
        $scope.created_toDel =  createdToDelformated_date;
      }
      let expectedDeliveryToDel = $scope.expectedDeliveryToDel;
      if(!!expectedDeliveryToDel){
        let expectedDeliveryToDelformated_date = expectedDeliveryToDel.format("yyyy-mm-dd");
        $scope.expected_delivery_toDel =  expectedDeliveryToDelformated_date;
      }
      let createdToOrd = $scope.createdToOrd;
      if(!!createdToOrd){
        let createdToOrdformated_date = createdToOrd.format("yyyy-mm-dd");
        $scope.created_toOrd =  createdToOrdformated_date;
      }
      let expectedDeliveryToOrd = $scope.expectedDeliveryToOrd;
      if(!!expectedDeliveryToOrd){
        let expectedDeliveryToOrdformated_date = expectedDeliveryToOrd.format("yyyy-mm-dd");
        $scope.expected_delivery_toOrd =  expectedDeliveryToOrdformated_date;
      }
  }
    $scope.searchTerm;
    $scope.clearSearchTerm = function() {
        $scope.searchTerm = '';
    };
    // The md-select directive eats keydown events for some quick select
    // logic. Since we have a search input here, we don't need that logic.
    $element.find('input').on('keydown', function(ev) {
        ev.stopPropagation();
    });
  // duble
  $scope.ChangeChechbox = function (){
    for(let item in $scope.defaultStatusOrder){
        document.getElementById($scope.defaultStatusOrder[item]).checked=false;
      }
    for(let item in $scope.selectedVegetables){
        document.getElementById($scope.selectedVegetables[item]).checked=true;
      }
    for(let item in $scope.defaultClientOrder){
        document.getElementById($scope.defaultClientOrder[item]).checked=false;
      }
    for(let item in $scope.selectedVegetablesClient){
        document.getElementById($scope.selectedVegetablesClient[item]).checked=true;
      }
      for(let item in $scope.selectedRoles){
        console.log($scope.selectedVegetablesClient[item])
      }


  }
  $scope.defaultClientOrderOnload = function(){
    $scope.defaultStatusOrderOnload = {};
    $scope.defaultClientOrderOnload = {};
    for(let item in $scope.defaultStatusOrder){
        if(document.getElementById($scope.defaultStatusOrder[item]).checked){
        $scope.defaultStatusOrderOnload[$scope.defaultStatusOrder[item]] = true;
        }
    }
    for(let item in $scope.defaultClientOrder){
        if(document.getElementById($scope.defaultClientOrder[item]).checked){
          $scope.defaultClientOrderOnload[$scope.defaultClientOrder[item]] = true;
        }
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
          $scope.DeleteItemsCreates = function (ev, id) {
              $mdDialog.show({
                  locals: {
                      theScope: {
                          title: 'Удалить статью затрат?',
                          buttonName: 'Удалить',
                          message: 'Вы действительно хотите удалить статью затрат?',
                          id: id,
                          url: '/cost_items/delete/',
                          urlRedirect: '/cost_items/'
                      }
                  },
                  controller: DeleteItemsCreatess,
                  templateUrl: '/dialog/confirm.html',
                  // templateUrl: '/cost_items/delete/'+id,
                  parent: angular.element(document.body),
                  targetEvent: ev,
                  clickOutsideToClose:true,
                  fullscreen: $scope.customFullscreen
              })
          }

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
          $scope.EditsItemsCreates = function(ev,id) {
            $mdDialog.show({
              controller: EditsItemsCreatess,
              templateUrl: '/cost_items/edit/'+id,
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
          $scope.OpenModalShowUser = function(ev, id) {
            $mdDialog.show({
              controller: OpenModalUserCtrl,
              templateUrl: '/users/edit/'+id,
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
        $scope.DeleteItemsCreates = function (ev, id) {
            $mdDialog.show({
                locals: {
                    theScope: {
                        title: 'Удалить спецификацию?',
                        buttonName: 'Удалить',
                        message: 'Вы действительно хотите удалить спецификацию?',
                        id: id,
                        url: '/specifications/delete/',
                        urlRedirect  : '/specifications/'
                    }
                },
                controller: DeleteItemsCreatess,
                templateUrl: '/dialog/confirm.html',
                // templateUrl: '/cost_items/delete/'+id,
                parent: angular.element(document.body),
                targetEvent: ev,
                clickOutsideToClose:true,
                fullscreen: $scope.customFullscreen
            })
        }
    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
  function OpenModalUserCtrl($scope, $mdDialog, $http) {
  $scope.title = 'Редактирование пользователя'
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
  function EditsItemsCreatess($scope, $mdDialog, $http) {
    $scope.title = 'Редактирование статьи затрат';
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
  function DeleteItemsCreatess($scope, $mdDialog, theScope, $http) {
    $scope.title = 'Удаление статьи затрат';
    $scope.sendOk = function (){
        $http.get(theScope.url+theScope.id)
            .then(function () {
                location.href = theScope.urlRedirect
            })
    }

      $scope.data = theScope;

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


  function SetLimit($scope, $mdDialog, $http) {
    $scope.title = 'Установка лимитов';
    $scope.hide = function() {
      $mdDialog.hide();
    };
      $scope.SetLimits = function(ev, id) {
          $mdDialog.show({
              controller: SetLimit,
              templateUrl: '/clients/'+id+'/limits/set',
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
    $scope.cancel = function() {
      $mdDialog.cancel();
    };

    $scope.answer = function(answer) {
      $mdDialog.hide(answer);
    };
  }
