jQuery(function($){

  // Handle WordPress native pagination with AJAX
  $('.shop-pagination').on('click', '.pagination-arrow, .dot', function(e) {
    e.preventDefault();

    if ($(this).prop('disabled') || $(this).hasClass('active')) {
      return;
    }

    var page = $(this).data('page');
    if (!page) return;

    console.log('Loading page:', page);

    // Show loading state
    $('.products-grid').addClass('loading');
    $(this).prop('disabled', true);

    // Build URL with page parameter
    var currentUrl = new URL(window.location);
    currentUrl.searchParams.set('paged', page);

    // Update browser history
    window.history.pushState({page: page}, '', currentUrl);

    // Load new content via AJAX
    $.get(currentUrl.toString())
      .done(function(data) {
        var $newContent = $(data);
        var $newProducts = $newContent.find('.products-grid');
        var $newPagination = $newContent.find('.shop-pagination');

        // Replace content with fade effect
        $('.products-grid').fadeOut(200, function() {
          $(this).html($newProducts.html()).fadeIn(300);
          $(this).removeClass('loading');
        });

        // Update pagination
        $('.shop-pagination').html($newPagination.html());

        // Scroll to top of products
        $('html, body').animate({
          scrollTop: $('.products-container').offset().top - 100
        }, 500);

        console.log('Page loaded successfully');
      })
      .fail(function() {
        console.error('Failed to load page');
        $('.products-grid').removeClass('loading');
        $(this).prop('disabled', false);
      });
  });

  // Handle browser back/forward buttons
  window.addEventListener('popstate', function(e) {
    if (e.state) {
      if (e.state.page || e.state.filter) {
        location.reload(); // Simple reload for back/forward
      }
    }
  });

  // Filter functionality - go to page 1 first, then filter
  $('.filter-btn').on('click', function(){
    var filter = $(this).data('filter');
    console.log('Filter clicked:', filter);

    $('.filter-btn').removeClass('active');
    $(this).addClass('active');

    // If we're not on page 1, first load page 1, then apply filter
    var currentPage = $('.pagination-dots .dot.active').text() || '1';

    if (currentPage !== '1' && filter !== 'all') {
      console.log('Not on page 1, loading page 1 first...');

      // Show loading state
      $('.products-grid').addClass('loading');

      // Build URL for page 1
      var currentUrl = new URL(window.location);
      currentUrl.searchParams.delete('paged'); // This takes us to page 1

      // Update browser history
      window.history.pushState({page: 1, filter: filter}, '', currentUrl);

      // Load page 1 first
      $.get(currentUrl.toString())
        .done(function(data) {
          var $newContent = $(data);
          var $newProducts = $newContent.find('.products-grid');
          var $newPagination = $newContent.find('.shop-pagination');

          // Replace content
          $('.products-grid').html($newProducts.html()).removeClass('loading');
          $('.shop-pagination').html($newPagination.html());

          // Now apply the filter to the page 1 content
          console.log('Page 1 loaded, now applying filter:', filter);
          applyFilter(filter);

          // Scroll to top of products
          $('html, body').animate({
            scrollTop: $('.products-container').offset().top - 100
          }, 500);
        })
        .fail(function() {
          console.error('Failed to load page 1');
          $('.products-grid').removeClass('loading');
        });
    } else {
      // We're already on page 1 or showing all, just apply filter directly
      applyFilter(filter);
    }
  });

  // Function to apply filter to current page content
  function applyFilter(filter) {
    console.log('Applying filter:', filter);

    var totalProducts = 0;
    var visibleProducts = 0;

    $('.product-card').each(function(){
      totalProducts++;
      var cats = ($(this).data('category') || '').toString().split(/\s+/);
      var shouldShow = filter === 'all' || cats.indexOf(filter) > -1;

      // Debug logging for each product
      console.log('Product #' + totalProducts + ':', {
        title: $(this).find('.product-name a').text(),
        categories: cats,
        shouldShow: shouldShow,
        filterLooking: filter
      });

      if (shouldShow) {
        visibleProducts++;
        $(this).show();
      } else {
        $(this).hide();
      }
    });

    console.log('Filter results:', {
      filter: filter,
      totalProducts: totalProducts,
      visibleProducts: visibleProducts
    });

    // Hide pagination when filtering (since it's per-page filtering)
    if (filter !== 'all') {
      $('.shop-pagination').hide();
    } else {
      $('.shop-pagination').show();
    }
  }

  console.log('WordPress pagination initialized');
});
