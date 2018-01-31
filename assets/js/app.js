import 'bootstrap';
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
    // test Masonry
    var $grid = $('.result');
    // let $grid = $('#listingTaxref')
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
                <div class="table">\
                    <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                    <h4 class="card-caption">\
                        <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                    </h4>\
                    <div class="card-description">{{ nomVernType }}</div>\
                    <div class="ftr text-center">\
                        <a href="/taxref/{{ slug }}" class="btn btn-secondary">Détails</a>\
                    </div>\
                </div>\
            </div>';
        var taxrefTemplateWithImg = '\
            <div class="card card-blog">\
                <div class="card-image">\
                    <a href="/taxref/{{ slug }}"> <img class="img" src="{{ url }}" alt="{{ alt }}"> </a>\
                </div>\
                <div class="table">\
                    <h6 class="category text-secondary">{{ lbAuteurType }}</h6>\
                    <h4 class="card-caption">\
                        <a href="/taxref/{{ slug }}">{{ lbNomType }}</a>\
                    </h4>\
                    <div class="card-description">{{ nomVernType }}</div>\
                    <div class="ftr text-center">\
                        <a href="/taxref/{{ slug }}" class="btn btn-secondary">Détails</a>\
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
                        var doc = new DOMParser().parseFromString(taxrefTemplateWithImg.render(item),'text/html');
                    } else {
                        var doc = new DOMParser().parseFromString(taxrefTemplate.render(item),'text/html');
                    }
                    items[i] = doc.body.firstChild;
                });
                $grid.append(items).masonry( 'appended', items );

            }
        })

        e.preventDefault();
    })

});


