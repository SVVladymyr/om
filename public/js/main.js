$('.authorization-container-title').click(function () {
   $('.authorization-container-title').removeClass('tabs-active');
   $(this).addClass('tabs-active');
});


  //Chosen
  $(".limitedNumbChosen").chosen({
		max_selected_options: 2,
    placeholder_text_multiple: "Which are two of most productive days of your week"
	})
	.bind("chosen:maxselected", function (){
		window.alert("You reached your limited number of selections which is 2 selections!");
	})

for(var i = 0; i < $('.open-list').length ; i++){
  let sesStOpen = sessionStorage.getItem('open-tree');
	if(!$($('.open-list')[i]).parent().siblings('ul').find('li').length){
		$($('.open-list')[i]).hide()
	}
  if(!!sesStOpen){
    if(sesStOpen.indexOf('.'+$($('.open-list')[i]).attr('data-open-id')+'.') != -1 ) {
      $($('.open-list')[i]).parent().siblings('ul').show() ; $($('.open-list')[i]).text('--')
    }
  }
}

$('.logout-btn').click(function(){
  sessionStorage.setItem('open-tree','')
})

function strSub(id){
  let sesStOpen = sessionStorage.getItem('open-tree');
  let HomeLenSes = sesStOpen.indexOf('.'+id+'.');
  if(HomeLenSes != -1){
    let afterSesStOpen = sesStOpen.substr(0, HomeLenSes);
    let beforeSesStOpen = sesStOpen.substr(HomeLenSes + ('.'+id+'.').length, sesStOpen.length - ('.'+id+'.').length);
    return afterSesStOpen+beforeSesStOpen;
  } else return sesStOpen
}

$('.open-list').click(function(){
  let sesStOpen = sessionStorage.getItem('open-tree');
  if($(this).text() == '+'){
    sessionStorage.setItem('open-tree', !sesStOpen ? '.'+$(this).attr('data-open-id')+'.' : sesStOpen + '.'+$(this).attr('data-open-id')+'.')
    $(this).parent().siblings('ul').show()
    $(this).text('--')
  } else if($(this).text() == '--'){
    // $(this).parent().siblings('ul').hide()
    for(var i = 0; i < $(this).parent().parent().find('.open-list').length; i++){
      sessionStorage.setItem('open-tree', strSub($($(this).parent().parent().find('.open-list')[i]).attr('data-open-id')))
    }
    $(this).parent().parent().find('ul').hide()
    $(this).parent().parent().find('.open-list').text('+')
    $(this).text('+')
  }
})
var forEach=function(t,o,r){if("[object Object]"===Object.prototype.toString.call(t))for(var c in t)Object.prototype.hasOwnProperty.call(t,c)&&o.call(r,t[c],c,t);else for(var e=0,l=t.length;l>e;e++)o.call(r,t[e],e,t)};
var hamburgers = document.querySelectorAll(".hamburger");
if (hamburgers.length > 0) {
    forEach(hamburgers, function(hamburger) {
        hamburger.addEventListener("click", function() {
            this.classList.toggle("is-active");
            $('body').toggleClass("active");
            let statusMenu = window.localStorage.getItem('statusMenu');
            window.localStorage.setItem('statusMenu', statusMenu == "true" ? "false" : "true")
        }, false);
    });
}

$('.open-modal-specification').click(function () {
   let id = $(this).attr('data-id');
   $.ajax({
       url: '/specifications/'+id,
       method: "GET",
       success: function(data){
         let lengthDataStart = data.indexOf('<div style="padding: 20px;" class="mobile-toogle">');
         let lengthDataEnd  = data.indexOf('<end-table-specification />');
         data = data.substr(lengthDataStart, lengthDataEnd-lengthDataStart);
         $('.modal .modal-content').html(data);
         $('.modal').show()
       }
   })
});

$('.open-modal-cost-item-create').click(function () {
   $.ajax({
       url: '/cost_items/create/',
       method: "GET",
       success: function(data){
         let lengthDataStart = data.indexOf('<h1 class="center-h1">Создание статьи затрат</h1>');
         let lengthDataEnd  = data.indexOf('<end-table-cost_items />');
         data = data.substr(lengthDataStart, lengthDataEnd-lengthDataStart);
         $('.modal .modal-content').html(data);
         $('.modal').show()
       }
   })
});

$('#user-ask').click(function () {
  $('.modal').show()
});



let deleteItem = (url)=>{
  $('.modal').hide();
  $('.modal-if-delete > a').attr('href',url)
  $('.modal-if-delete').show();

}

$('.edit-user-modal').click(function () {
   let id = $(this).attr('data-id');
   $.ajax({
       url: '/users/edit/'+id,
       method: "GET",
       success: function(data){
         let lengthDataStart = data.indexOf('<open-modal>');
         let lengthDataEnd  = data.indexOf('<close-modal>');
         data = data.substr(lengthDataStart, lengthDataEnd-lengthDataStart);
         $('.modal .modal-content').html(data);
         $('.modal').show()
       }
   })
});

$('.set-limit-modal').click(function () {
   let id = $(this).attr('data-id');
   $.ajax({
       url: '/clients/'+id+'/limits/set',
       method: "GET",
       success: function(data){
         let lengthDataStart = data.indexOf('<open-modal>');
         let lengthDataEnd  = data.indexOf('<close-modal>');
         data = data.substr(lengthDataStart, lengthDataEnd-lengthDataStart);
         $('.modal .modal-content').html(data);
         $('.modal').show()
       }
   })
});

$('.open-modal-cost-item-edit').click(function () {
   let id = $(this).attr('data-id');
   $.ajax({
       url: '/cost_items/edit/'+id,
       method: "GET",
       success: function(data){
         let lengthDataStart = data.indexOf('<h1 class="center-h1">Редактирование статьи затрат</h1>');
         let lengthDataEnd  = data.indexOf('<end-table-cost_items />');
         data = data.substr(lengthDataStart, lengthDataEnd-lengthDataStart);
         $('.modal .modal-content').html(data);
         $('.modal').show()
       }
   })
});

$("input#phone_number").mask("+38(999) 999-99-99");

// var wDelta = 120;
// function scrollDoc(e) {
//     if (!e) e = event;
//     if (e.preventDefault) { e.preventDefault(); } else { e.returnValue = false; }
//     var __delta = e.wheelDelta || -e.detail;
//     __delta /= Math.abs(__delta);
//     document.documentElement.scrollLeft -= __delta * wDelta;
//     if (this.attachEvent) return false;
//     document.body.scrollLeft -= __delta * wDelta;
// }
// window.onload = function() {
//     var html = document.documentElement;
//     if (html.attachEvent) {
//         html.attachEvent("onmousewheel", scrollDoc);
//     } else {
//         html.addEventListener("DOMMouseScroll", scrollDoc, false);
//         html.addEventListener("mousewheel", scrollDoc, false);
//     }
// };

for(let i = 0; i < $('.order-label').length; i++){
    $($('.order-label')[i]).height($($('.order-label')[i]).find('.create-edit-child > label:first-child').height())
}

$('.select-filter-dropdown').click(function(){
    $(this).next().toggleClass('active-select-filter-dropdown-list')
})

$( document ).ready(function() {
  var isChecked = [];
  for(var i = 0 ; i < $('.podr-order .select-filter-dropdown-list input').length; i++){
  	if($($('.podr-order .select-filter-dropdown-list input')[i]).is(':checked')) {
    	isChecked.push(true);
  		if(isChecked.length==1){
  			$('.podr-order .select-filter-dropdown > label').text($($('.podr-order .select-filter-dropdown-list input')[i]).next().text())
      } else {
        $('.podr-order .select-filter-dropdown > label').text('Выбрано '+isChecked.length+' параметра')
      }
  	}
    if(!isChecked.length)$('.podr-order .select-filter-dropdown > label').text('Не выбраны подразделения')
  }
  isChecked = [];
  for(var i = 0 ; i < $('.status-order .select-filter-dropdown-list input').length; i++){
  	if($($('.status-order .select-filter-dropdown-list input')[i]).is(':checked')) {
    	isChecked.push(true);
  		if(isChecked.length==1){
  			$('.status-order .select-filter-dropdown > label').text($($('.status-order .select-filter-dropdown-list input')[i]).next().text())
      } else {
        $('.status-order .select-filter-dropdown > label').text('Выбрано '+isChecked.length+' параметра')
      }
  	}
    if(!isChecked.length)$('.status-order .select-filter-dropdown > label').text('Не выбраны параметры')
  }
})

$('.podr-order .select-filter-dropdown-list').click(function(){
  var isChecked = [];
  for(var i = 0 ; i < $('.podr-order .select-filter-dropdown-list input').length; i++){
  	if($($('.podr-order .select-filter-dropdown-list input')[i]).is(':checked')) {
    	isChecked.push(true);
  		if(isChecked.length==1){
  			$('.podr-order .select-filter-dropdown > label').text($($('.podr-order .select-filter-dropdown-list input')[i]).next().text())
      } else {
        $('.podr-order .select-filter-dropdown > label').text('Выбрано '+isChecked.length+' параметра')
      }
  	}
    if(!isChecked.length)$('.podr-order .select-filter-dropdown > label').text('Не выбраны подразделения')
  }
})
$('.status-order .select-filter-dropdown-list').click(function(){
  var isChecked = [];
  for(var i = 0 ; i < $('.status-order .select-filter-dropdown-list input').length; i++){
  	if($($('.status-order .select-filter-dropdown-list input')[i]).is(':checked')) {
    	isChecked.push(true);
  		if(isChecked.length==1){
  			$('.status-order .select-filter-dropdown > label').text($($('.status-order .select-filter-dropdown-list input')[i]).next().text())
      } else {
        $('.status-order .select-filter-dropdown > label').text('Выбрано '+isChecked.length+' параметра')
      }
  	}
    if(!isChecked.length)$('.status-order .select-filter-dropdown > label').text('Не выбраны параметры')
  }
})

$('.order-label label:first-child').click(function(){
  if($(this).attr('data-status') == 'false'){
    $(this).parents('.order-label').height(205);
    $(this).parents('.order-label').css('overflow','auto');

  } else {
    $(this).parents('.order-label').height($(this).height());
    $(this).parents('.order-label').css('overflow','hidden');
  }
  $(this).find('span').toggleClass('rotage-active')
  $(this).attr('data-status', $(this).attr('data-status') == 'false' ? 'true' : 'false')
});

for (var i = 0; i < $('#accordion > div').length; i++) {
   var href = $($('#accordion > div')[i]).find('a').attr('href');
   var locationQ = location.href;
   if(locationQ.indexOf(href)  != -1 ){
       $($('#accordion > div')[i]).find('.left-menu-text a').css('color', 'white');
   }
}

$(function() {

    var i = 0;

    $("colgroup").each(function() {

        i++;

        $(this).attr("id", "col"+i);

    });

    var totalCols = i;

    i = 1;

    $("td").each(function() {

        $(this).attr("rel", "col"+i);

        i++;

        if (i > totalCols) { i = 1; }

    });

    $("td").hover(function() {

        $(this).parent().addClass("hover");
        var curCol = $(this).attr("rel");
        $("#"+curCol).addClass("hover");

    }, function() {

        $(this).parent().removeClass("hover");
        var curCol = $(this).attr("rel");
        $("#"+curCol).removeClass("hover");

    });

});

$(document).mouseup(function (e) {
    var container = $(".status-order");
    if (container.has(e.target).length === 0){
        container.find('.active-select-filter-dropdown-list').removeClass('active-select-filter-dropdown-list');
    }
});
$(document).mouseup(function (e) {
    var container = $(".podr-order");
    if (container.has(e.target).length === 0){
        container.find('.active-select-filter-dropdown-list').removeClass('active-select-filter-dropdown-list');
    }
});

$(document).mouseup(function (e) {
    var container = $(".modal-if-delete");
    if (container.has(e.target).length === 0){
        container.hide();
    }
});

// if(!!$('.error').length) setTimeout(()=>{
//   $('.error').addClass('error-time-out')
//   setTimeout(()=>{
//     $('.error').css('display','none')
//   },350)
// },4000)


$(document).mouseup(function (e) {
    var container = $(".modal");
    if (container.has(e.target).length === 0){
        container.hide();
    }
});
if((location.href).replace('http://'+location.host,'') == "/orders"){
  const theadToTop = $("thead").position().top;
  window.onscroll = function() {
   	if(theadToTop < window.pageYOffset - 1) {
      $('.first-table-tr').addClass('active-table')
  		$('thead th').css('top', window.pageYOffset - 1  - theadToTop)
  	} else {
      $('thead th').css('top', 0)
    }
  }
}
