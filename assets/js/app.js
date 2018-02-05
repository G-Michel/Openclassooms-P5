// require('bootstrap')
import Bootstrap from 'bootstrap';
import './jquery.instantSearch.js';
import './datepicker';

let jQueryBridget = require('jquery-bridget');
let Masonry = require('masonry-layout');

jQueryBridget( 'masonry', Masonry, $ );

$(function() {

  // SEARCH BAR
  //===========
  $('.search-field').instantSearch({
      delay: 300,
  });

  // DISPLAY MORE RESULTS
  //=====================

  // Masonry
  var $grid = $('.result');
  $grid.masonry({
      itemSelector: '.card',
      gutter: 20,
      itemAnimated: true,
  })

  $('.more-results').on('click', function(e) {

      var $form = $(this.closest('form'));
      var $spinner = $('#moreResult-spinner');
      var $btn = $('#moreResult-button');
      var nbItems = $grid.find('.card').length;
      var taxrefTemplate = '\
          <div class="card card-blog">\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">{{ nomVernType }}</div>\
                  <div class="ftr text-center">\
                      <a href="/taxref/{{ slug }}" class="btn {{ btnColor }}">Détails</a>\
                  </div>\
              </div>\
          </div>';
      var taxrefTemplateWithImg = '\
          <div class="card card-blog">\
              <div class="card-image">\
                  <a href="/taxref/{{ slug }}"> <img class="img" src="{{ url }}" alt="{{ alt }}"> </a>\
              </div>\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">{{ nomVernType }}</div>\
                  <div class="ftr text-center">\
                      <a href="/taxref/{{ slug }}" class="btn {{ btnColor }}">Détails</a>\
                  </div>\
              </div>\
          </div>';
          var taxrefTemplateForId = '\
          <div class="card card-blog">\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">{{ nomVernType }}</div>\
                  <div class="ftr text-center">\
                      <a href="{{ path }}" class="btn {{ btnColor }}">c\'est mon oiseau</a>\
                      <a href="/taxref/{{ slug }}" class="btn {{ btnColor }}">Détails</a>\
                  </div>\
              </div>\
          </div>';
      var taxrefTemplateForIdWithImg = '\
          <div class="card card-blog">\
              <div class="card-image">\
                  <a href="/taxref/{{ slug }}"> <img class="img" src="{{ url }}" alt="{{ alt }}"> </a>\
              </div>\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">{{ nomVernType }}</div>\
                  <div class="ftr text-center">\
                      <a href="{{ path }}" class="btn {{ btnColor }}">c\'est mon oiseau</a>\
                      <a href="/taxref/{{ slug }}" class="btn {{ btnColor }}">Détails</a>\
                  </div>\
              </div>\
          </div>';
          var obsTemplate = '\
          <div class="card card-blog">\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/observation/{{ id }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">\
                  {{ nomVernType }}\
                  <div class="ftr ">\
                      <div class="author col-9 p-0 text-truncate">\
                          <a href="#">\
                              <img src="{{ userUrl }}" alt="{{ userAlt }}" class="avatar img-raised">\
                              <span class="text-truncate">{{ user }}</span>\
                          </a>\
                      </div>\
                      <div class="stats d-inline-flex align-items-center col-3 p-0"> <i class="material-icons">access_time</i>{{ ago }}</div>\
                  </div>\
                  </div>\
              </div>\
          </div>';
      var obsTemplateWithImg = '\
          <div class="card card-blog">\
              <div class="card-image">\
              <a href="/observation/{{ id }}"> <img class="img" src="{{ url }}" alt="{{ alt }}"></a>\
              </div>\
              <div class="table {{ backgroundTable }}">\
                  <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                  <h4 class="card-caption">\
                      <a href="/observation/{{ id }}">{{ lbNomType }}</a>\
                  </h4>\
                  <div class="card-description">\
                  {{ nomVernType }}\
                      <div class="ftr">\
                          <div class="author col-9 p-0 text-truncate">\
                              <a href="#">\
                                  <img src="{{ userUrl }}" alt="{{ userAlt }}" class="avatar img-raised">\
                                  <span class="text-truncate">{{ user }}</span>\
                              </a>\
                          </div>\
                          <div class="stats d-inline-flex align-items-center  col-3 p-0"> <i class="material-icons">access_time</i>{{ ago }}</div>\
                      </div>\
                  </div>\
              </div>\
          </div>';
      var data = [];
      data = {
          'q' : $_GET('q'),
          'o' : nbItems-1
      }
      // display spinner and cache btn
      $spinner.show()
      $btn.hide()

      // Ajax request
      var jqxhr = $.getJSON($form.attr('action'), data)
      .done(function(items) {
          if (0 === items.length) {
              $spinner.hide()
          } else {
              $spinner.hide()
              $btn.show()
              $.each(items, function (i, item) {
                  if (item.url) {
                      if (item.page == 'taxref') {
                          if (item.path) {
                              var doc = new DOMParser().parseFromString(taxrefTemplateForIdWithImg.render(item),'text/html');
                          } else {
                              var doc = new DOMParser().parseFromString(taxrefTemplateWithImg.render(item),'text/html');
                          }
                      }else {
                          var doc = new DOMParser().parseFromString(obsTemplateWithImg.render(item),'text/html');
                      }
                  } else {
                      if (item.page == 'taxref') {
                        if (item.path) {
                            var doc = new DOMParser().parseFromString(taxrefTemplateForId.render(item),'text/html');
                        } else {
                            var doc = new DOMParser().parseFromString(taxrefTemplate.render(item),'text/html');
                        }
                      }else {
                          var doc = new DOMParser().parseFromString(obsTemplate.render(item),'text/html');;
                      }
                  }
                  items[i] = doc.body.firstChild;
              });
              $grid.append(items).masonry( 'appended', items );

          }
      })

      e.preventDefault();

      // Find param query in url
      function $_GET(param) {
          var vars = {};
          window.location.href.replace( location.hash, '' ).replace(
              /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
              function( m, key, value ) { // callback
                  vars[key] = value !== undefined ? value : '';
              }
          );

          if ( param ) {
              return vars[param] ? vars[param] : null;
          }
          return vars;
      }
  })

});


//Update notifications
  $('.dropdown-event-listener').on('hidden.bs.dropdown', function () {
    var result = $.ajax({
      url : symfoUrlRoute,
      type : 'GET',
      data : 'seen=desktop',
      dataType: 'html',
      success : function(code_html, status){
        if (code_html == 'nothing to flush'){}
        else{
          $('.notificationAreaNav').replaceWith(code_html);
        }
      }
    });
  });


//Update notifications mobile
  $('.navbarMobile').on('hidden.bs.dropdown', function () {
    var result = $.ajax({
      url : symfoUrlRoute,
      type : 'GET',
      data : 'seen=mobile',
      dataType: 'html',
      success : function(code_html, status){
        if (code_html == 'nothing to flush'){}
        else{
        $('.navbarMobile').on('hidden.bs.dropdown', function () {
          $('.mobile-notif-updater').replaceWith(code_html);});
        }
      }
    });
  });


//Accept cookies
  $('.accept-cookie-policy button').on('click', function (event) {
    console.log("prout");
    event.preventDefault();
    var result = $.ajax({
      url : cookiesAccepted,
      type : 'GET',
      data : 'cookie=ok',
      dataType: 'html',
      success : function(){
    $(".alert").alert('close');

      }
    });
  });

  $('[data-toggle="tooltip"]').tooltip();


  //===========
  //  ADMIN
  //===========

  $(function() {

    // Datepicker date obs new
    // $('#observe_bird_moment_dateObs').datetimepicker();
    // Datepicker date obs edit
    // $('#observation_dateObs').datetimepicker();

    // Choose file picture
    bs_input_file();

    // Slider number birds
    if (document.getElementById("observe_bird_detail_bird_birdNumber")) {
      var slider = document.getElementById("observe_bird_detail_bird_birdNumber");
      var output = document.getElementById("birdNumber");
      output.innerHTML = 'Nombre d\'oiseaux : ' + slider.value; // Display the default slider value

      // Update the current slider value (each time you drag the slider handle)
      slider.oninput = function() {
          output.innerHTML = 'Nombre d\'oiseaux : ' + this.value;
      }
    }

  });
// Function Choose file picture NEW
  function bs_input_file() {
    $(".input-file").before(
      function() {
        if ( $(this).prev().hasClass('form-control-file') ) {
          var element = $("#observe_bird_detail_picture_file");
          if (element.length == 0) {
              element = $("#observation_picture_file");
              if (element.length == 0) {
                element = $("#edit_profile_picture_file");
              }
          }
          element.addClass('d-none')
          element.change(function(){
            element.next(element).find('input').val((element.val()).split('\\').pop());
          });
          $(this).find("button.btn-choose").click(function(){
            element.click();
          });
          // $(this).find("button.btn-reset").click(function(){
          //   element.val(null);
          //   $(this).parents(".input-file").find('input').val('');
          // });
          $(this).find('input').css("cursor","pointer");
          $(this).find('input').mousedown(function() {
            $(this).parents('.input-file').prev().click();
            return false;
          });
          return element;
        }
      }
    );
  }

// // Function Choose file picture EDIT
//   function bs_input_file_edit() {
//     $(".input-file").before(
//       function() {
//         if ( $(this).prev().hasClass('form-control-file') ) {
//           var element = $("#observation_picture_file");
//           element.addClass('d-none')
//           element.change(function(){
//             element.next(element).find('input').val((element.val()).split('\\').pop());
//           });
//           $(this).find("button.btn-choose").click(function(){
//             element.click();
//           });
//           // $(this).find("button.btn-reset").click(function(){
//           //   element.val(null);
//           //   $(this).parents(".input-file").find('input').val('');
//           // });
//           $(this).find('input').css("cursor","pointer");
//           $(this).find('input').mousedown(function() {
//             $(this).parents('.input-file').prev().click();
//             return false;
//           });
//           return element;
//         }
//       }
//     );
//   }

// Handling the modal confirmation message.
  $(document).on('submit', 'form[data-confirmationDelete]', function (event) {
    var $form = $(this),
        $confirm = $('#deleteConfirmationModal');

        if ($confirm.data('result') !== 'yes') {
          //cancel submit event
          event.preventDefault();

          $confirm
          .off('click', '#btnYes')
          .on('click', '#btnYes', function () {
                $confirm.data('result', 'yes');
                $form.find('input[type="submit"]').attr('disabled', 'disabled');
                $form.submit();
            })
            .modal('show');
    }
  });

// Handling the modal confirmation message.
  $(document).on('submit', 'form[data-confirmationCheck]', function (event) {
    var $form = $(this),
        $confirm = $('#checkConfirmationModal');

    if ($confirm.data('result') !== 'yes') {
        //cancel submit event
        event.preventDefault();

        $confirm
            .off('click', '#btnYes')
            .on('click', '#btnYes', function () {
                var $check = $('input[name=customRadio]:checked').val();
                $confirm.data('result', 'yes');
                $form.find('#statusCode').attr('value', $check);
                $form.find('input[type="submit"]').attr('disabled', 'disabled');
                $form.submit();
            })
            .modal('show');
    }
  });




