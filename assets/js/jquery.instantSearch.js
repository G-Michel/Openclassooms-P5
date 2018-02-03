/**
 * jQuery plugin for an instant searching.
 *
 * @author Oleg Voronkovich <oleg-voronkovich@yandex.ru>
 * @author Yonel Ceruto <yonelceruto@gmail.com>
 */
(function ($) {
  'use strict';

  String.prototype.render = function (parameters) {
      return this.replace(/({{ (\w+) }})/g, function (match, pattern, name) {
          return parameters[name];
      })
  };

  // INSTANTS SEARCH PUBLIC CLASS DEFINITION
  // =======================================

  var InstantSearch = function (element, options) {
      this.$input = $(element);
      this.$form = this.$input.closest('form');
      this.$preview = $('<div id="resultsTaxref" class="card-columns p-3">').replaceAll($('#resultsTaxref'));
      this.options = $.extend({}, InstantSearch.DEFAULTS, this.$input.data(), options);

      this.$input.on('keydown', function(e) {
        if (e.which == 13) {
            // e.preventDefault();
        }
      });
    //   this.$form.on('submit', function(e) {
    //     e.preventDefault();
    //   });

      this.$input.keyup(this.debounce());
  };

  InstantSearch.DEFAULTS = {
      minQueryLength: 2,
      limit: 10,
      delay: 500,
      noResultsMessage: 'No results found',
      itemTemplate: '\
      <div class="card card-blog card-search">\
            <div class="card-image">\
                <a href="/taxref/{{ slug }}"> <img class="img" src="{{ url }}" alt="{{ alt }}"> </a>\
            </div>\
            <div class="table ">\
                <h6 class="category text-secondary">{{ lbNomType }}</h6>\
                <h4 class="card-caption">\
                <a href="/taxref/{{ slug }}">{{ lbAuteurType }}</a>\
                </h4>\
                <div class="card-description">{{ nomVernType }}</div>\
                <div class="ftr text-center">\
                <a href="/taxref/{{ slug }}" class="btn btn-secondary">Détails</a>\
                </div>\
            </div>\
        </div>',
        moreTemplate: '\
        <div class="card card-blog card-search">\
            <div class="ftr text-center h-100 p-0 m-0">\
                <a href="#" onclick="document.getElementById(\'search-form\').submit();" class="btn btn-primary h-100 w-100 p-0 m-0 d-flex flex-column justify-content-center">\
                <i class="material-icons md-60">add</i></a>\
            </div>\
        </div>'
  };

  InstantSearch.prototype.debounce = function () {
      var delay = this.options.delay;
      var search = this.search;
      var timer = null;
      var self = this;

      return function () {
          clearTimeout(timer);
          timer = setTimeout(function () {
              search.apply(self);
          }, delay);
      };
  };

  InstantSearch.prototype.search = function () {
      //   to display the spinner between each search
      //   this.$preview.empty();

      var query = $.trim(this.$input.val()).replace(/\s{2,}/g, ' ');
      if (query.length < this.options.minQueryLength) {
          this.$preview.empty();
          return;
      }
      //   shows the spinner
      if ($('#search-spinner').length == 0){
          this.$preview.append('<p id="search-spinner" class="pt-5 m-0 text-center text-white w-100"><i class="fa fa-spinner fa-spin" aria-hidden="true">')
      }

      var self = this;
      var data = this.$form.serializeArray();
      data['l'] = this.limit;

      var jqxhr = $.getJSON(this.$form.attr('action'), data)
        .done(function( items ) {
            self.show(items);
        });
  };

  InstantSearch.prototype.show = function (items) {
      var $preview = this.$preview;
      var itemTemplate = this.options.itemTemplate;
      var moreTemplate = this.options.moreTemplate;

      if (0 === items.length) {
          $preview.html(this.options.noResultsMessage);
      } else {
          $preview.empty();
          $.each(items, function (index, item) {
              $preview.append(itemTemplate.render(item));
            });
            $preview.append(moreTemplate);
      }
  };

  // INSTANTS SEARCH PLUGIN DEFINITION
  // =================================

  function Plugin(option) {
      return this.each(function () {
          var $this = $(this);
          var instance = $this.data('instantSearch');
          var options = typeof option === 'object' && option;

          if (!instance) $this.data('instantSearch', (instance = new InstantSearch(this, options)));

          if (option === 'search') instance.search();
      })
  }

  $.fn.instantSearch = Plugin;
  $.fn.instantSearch.Constructor = InstantSearch;

})(window.jQuery);
