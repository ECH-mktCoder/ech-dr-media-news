(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(function () {
    const ajaxurl = $('.ech-dmn-news-container .news-list').data("ajaxurl");
    const ppp = $('.ech-dmn-news-container .news-list').data('ppp');
    const defaultDr = $('.ech-dmn-news-container .news-list').data('dr');
    const defaultSpec = $('.ech-dmn-news-container .news-list').data('specialties');
    const defaultBrand = $('.ech-dmn-news-container .news-list').data('brand');
    let dr = defaultDr;
    let specialties = defaultSpec;
    let brand = defaultBrand;
    let page = $('.ech-dmn-news-container .news-list').data('page');
    let isFetching = false;

    const updateButtonStates = () => {
      const isChecked = $('.ech-dmn-filter-container input[type="checkbox"]:checked').length > 0;
      $('#newsSearchBtn, #resetBtn').prop('disabled', !isChecked || isFetching);
    };

    const fetchNews = (data,action) => {
      isFetching = true;
      updateButtonStates();

      $.ajax({
        url: ajaxurl,
        type: "post",
        data: {
          ...data,
          action: action,
        },
        success: function (res) {
          const jsonObj = JSON.parse(res);
          // console.log(jsonObj);
          $('.ech-dmn-news-container .loading-news').hide();
          $('.ech-dmn-news-container .news-list').attr('data-page', jsonObj.current_page);
          $('.ech-dmn-news-container .news-list').append(jsonObj.html);
          $('#moreNewsBtn').toggle(jsonObj.current_page != jsonObj.max_page);
        },
        error: function (res) {
          console.error(res);
        },
        complete: function () {
          isFetching = false;
          updateButtonStates();
        }
      });
    };

    $('.ech-dmn-filter-container input[type="checkbox"]').change(function () {
      updateButtonStates();
    });

    $('#moreNewsBtn').on('click', function () {
      const action = 'ECHD_load_more_posts';
      if (isFetching) return;
      $('.ech-dmn-news-container .loading-news').show();
      $(this).hide();
      page++;
      fetchNews({
        ppp: ppp,
        page: page,
        specialties: specialties,
        dr: dr,
        brand: brand,
      },action);
    });

    $('#newsSearchBtn').on('click', function () {
      const action = 'ECHD_filter_news_list';

      if (isFetching) return;
      $('html, body').animate({ scrollTop: 0 }, 'slow');
      $('.ech-dmn-news-container .news-list').empty();
      $('.ech-dmn-news-container .loading-news').show();
      $('#moreNewsBtn').hide();
      page = 1;
      dr = $('.ech-dmn-filter-container input[name="doctor[]"]:checked').map(function () {
        return $(this).val();
      }).get().join(',');

      specialties = $('.ech-dmn-filter-container input[name="specialty[]"]:checked').map(function () {
        return $(this).val();
      }).get().join(',');

      brand = $('.ech-dmn-filter-container input[name="brand[]"]:checked').map(function () {
        return $(this).val();
      }).get().join(',');

      $('.ech-dmn-news-container .news-list').attr('data-dr', dr).attr('data-specialties', specialties).attr('data-brand', brand);

      fetchNews({
        ppp: ppp,
        page: page,
        specialties: specialties,
        dr: dr,
        brand: brand,
      },action);
    });

    $('#resetBtn').on('click', function () {
      if (isFetching) return;
      const action = 'ECHD_filter_news_list';

      $('.ech-dmn-filter-container input[type="checkbox"]:checked').prop('checked', false);
      updateButtonStates();
      if (dr || specialties || page !== 1) {
        $('html, body').animate({ scrollTop: 0 }, 'slow');
        $('.ech-dmn-news-container .news-list').empty();
        $('.ech-dmn-news-container .loading-news').show();
        $('#moreNewsBtn').hide();
        dr = defaultDr;
        specialties = defaultSpec;
        brand = defaultBrand;
        page = 1;
        $('.ech-dmn-news-container .news-list').attr('data-dr', dr).attr('data-specialties', specialties);

        fetchNews({
          ppp: ppp,
          page: page,
          dr: dr,
          specialties: specialties,
          brand: brand,
        },action);
      }
    });

  }); // ready

})( jQuery );
