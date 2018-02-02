// import 'bootstrap';
import './jquery.instantSearch.js';

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
        gutter: 30,
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
                            var doc = new DOMParser().parseFromString(taxrefTemplateWithImg.render(item),'text/html');
                        }else {
                            var doc = new DOMParser().parseFromString(obsTemplateWithImg.render(item),'text/html');
                        }
                    } else {
                        if (item.page == 'taxref') {
                            var doc = new DOMParser().parseFromString(taxrefTemplate.render(item),'text/html');
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
    })

});



//Update notifications
  $('.dropdown-event-listener').on('hidden.bs.dropdown', function () {
    var result = $.ajax({
      url : symfoUrlRoute,
      type : 'GET',
      data : 'seen=true',
      dataType: 'html',
      success : function(code_html, status){
        if (code_html == 'nothing to flush'){}
        else{
          $('.notificationAreaNav').replaceWith(code_html);
        }
      }
    });
  });
