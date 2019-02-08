
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="../../favicon.ico">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=all" rel="stylesheet" type="text/css">
    <!--  <link rel="stylesheet" href="/css/newstyle.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.1.1/min/dropzone.min.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="/css/common.css" />
    <link rel="stylesheet" href="/css/chosen.css" />
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/hamburgers.css" />
    <link rel="stylesheet" href="/css/newStyle2.css">
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-animate.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-aria.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-messages.min.js"></script>
    <script src="//ajax.googleapis.com/ajax/libs/angularjs/1.5.5/angular-sanitize.js"></script>
    <script>addEventListener("keydown", function(e){if(e.keyCode == 123){alert('И что вы тут забыли?')}}); </script>

    <script src="https://ajax.googleapis.com/ajax/libs/angular_material/1.1.0/angular-material.min.js"></script>
    <title>OM24: CRM system</title>
</head>
<body  ng-app="BlankApp">
<div id="p_prldr">
    <div class="preloader">
        <div class="preloader__clock">
            <div class="preloader__arrow"></div>
        </div>
    </div>
</div>
@include('layouts.mast_nav')
<!--  <div class="content-right">
      <div class="container-fluid">
        <div class="col-xs-12">
          <button class="hamburger hamburger--arrow" type="button">
            <span class="hamburger-box">
              <span class="hamburger-inner"></span>
            </span>
          </button>
          <script type="text/javascript">
            let statusMenu = window.localStorage.getItem('statusMenu');
            if(statusMenu == "true") {
              document.getElementsByTagName("body")[0].className += " active";
            } else document.getElementsByClassName("hamburger")[0].className += " is-active";
          </script> -->
@yield('content')
<!--  </div>
      </div>
    </div>
    <div class="modal-if-delete">
        <h2 class="modal-title">Вы действительно хотите удалить?</h2>
        <div class="close" onclick="$('.modal-if-delete').hide()">X</div>
        <a href="#" class="delete-modal-success btn btn-large btn-danger">Удалить</a>
        <div class="delete-modal-cancel btn btn-large btn-success"  onclick="$('.modal-if-delete').hide()">Отмена</div>
    </div> -->
@include('layouts.side_bar')
@include('layouts.mast_footer')
</body>
</html>
