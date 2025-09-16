<?php
/**
 * Template part: Featured products (t-shirts) carousel
 * - Progressive lazy-loading
 * - Desktop arrows jump by 5 items
 * - "Wind" tilt on page scroll, carousel scroll, and arrow clicks
 *
 * @package Misty_House
 */

defined('ABSPATH') || exit;

$arrow_left   = get_template_directory_uri() . '/assets/images/arrow_left.png';
$arrow_right  = get_template_directory_uri() . '/assets/images/arrow_right.png';
$placeholder  = get_template_directory_uri() . '/assets/images/t-shirt.png';

/* +++ NEW: pattern bg + fallback +++ */
$pattern_url = get_template_directory_uri() . '/assets/images/patterbn.jpg';
if ( ! file_exists( get_template_directory() . '/assets/images/patterbn.jpg' ) ) {
  $pattern_url = get_template_directory_uri() . '/assets/images/pattern.jpg';
}

/** (Title riešite osobitne – tu ho zámerne nenechávam) */

/**
 * Query ALL featured products (no limit). If none are featured,
 * fall back to ALL published products ordered by date DESC.
 */
$featured_q = new WP_Query([
    'post_type'      => 'product',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'tax_query'      => [[
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['featured'],
        'operator' => 'IN',
    ]],
]);

if ( $featured_q->have_posts() ) {
    $products = $featured_q;
} else {
    $products = new WP_Query([
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ]);
}

$items_html = [];
if ( $products->have_posts() ) {
    while ( $products->have_posts() ) {
        $products->the_post();

        $img_id = get_post_thumbnail_id( get_the_ID() );
        $img    = $img_id ? wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' ) : '';
        $img    = $img ?: $placeholder;

        $link  = get_the_permalink();
        $title = get_the_title();

        ob_start(); ?>
        <div class="tshirt-item">
          <a href="<?php echo esc_url( $link ); ?>">
            <div class="tshirt-image-wrapper">
              <img
                class="lazy-tshirt lazy-fade"
                src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
                data-src="<?php echo esc_url( $img ); ?>"
                alt="<?php echo esc_attr( $title ); ?>"
                loading="lazy"
                decoding="async"
              >
            </div>
          </a>
        </div>
        <?php
        $items_html[] = ob_get_clean();
    }
    wp_reset_postdata();
}

$unique_count = count( $items_html );
if ( $unique_count === 0 ) {
    // bezpečný fallback – aspoň 5 placeholderov
    for ( $i = 0; $i < 5; $i++ ) {
        ob_start(); ?>
        <div class="tshirt-item placeholder">
          <div class="tshirt-image-wrapper">
            <img
              class="lazy-tshirt lazy-fade"
              src="data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=="
              data-src="<?php echo esc_url( $placeholder ); ?>"
              alt="<?php esc_attr_e( 'Placeholder T-shirt', 'misty-house' ); ?>"
              loading="lazy"
              decoding="async"
            >
          </div>
        </div>
        <?php
        $items_html[] = ob_get_clean();
    }
    $unique_count = 5;
}
?>
<section class="tshirts-section">
  <div class="tshirts-carousel-wrapper">

    <button class="tshirt-btn prev" aria-label="<?php esc_attr_e('Previous','misty-house'); ?>">
      <img src="<?php echo esc_url( $arrow_left ); ?>" alt="">
    </button>

    <div
      class="tshirts-container"
      id="tshirts-container"
      data-unique="<?php echo esc_attr( $unique_count ); ?>"
    >
      <?php
      foreach ( $items_html as $html ) { echo $html; } // phpcs:ignore
      if ( $unique_count > 1 ) { foreach ( $items_html as $html ) { echo $html; } } // phpcs:ignore
      ?>
    </div>

    <button class="tshirt-btn next" aria-label="<?php esc_attr_e('Next','misty-house'); ?>">
      <img src="<?php echo esc_url( $arrow_right ); ?>" alt="">
    </button>
  </div>
</section>

<style>

.tshirts-section{
background: url('<?php echo esc_url($pattern_url); ?>') center top repeat;
  /* ak chceš jemnejšie dlaždice, môžeš skúsiť aj:
     background-size: 480px auto;  (alebo 320px/256px podľa toho, čo sedí) */
}
/* Lazy fade (neovplyvní layout) */
.lazy-fade{ opacity:0; transition:opacity .35s ease; }
.lazy-fade.is-loaded{ opacity:1; }

/* rotácia okolo „háčika“ (hore) */
.tshirt-image-wrapper{ transform-origin:50% 0; will-change:transform; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var scroller = document.getElementById('tshirts-container');
  if (!scroller) return;

  var prev = document.querySelector('.tshirt-btn.prev');
  var next = document.querySelector('.tshirt-btn.next');

  var UNIQUE = parseInt(scroller.getAttribute('data-unique') || '0', 10);
  if (!UNIQUE) UNIQUE = Math.max(1, Math.floor(scroller.querySelectorAll('.tshirt-item').length / 2));

  // Start aligned
  var first = scroller.querySelector('.tshirt-item');
  if (first) scroller.scrollTo({ left: first.offsetLeft, behavior: 'auto' });

  function gapOf(el){ var ps = getComputedStyle(el); return parseFloat(ps.columnGap || ps.gap || 0) || 0; }
  function cardWidth(){
    var card = scroller.querySelector('.tshirt-item');
    if (!card) return 200;
    return card.getBoundingClientRect().width + gapOf(scroller);
  }
  function currentIndex(){ var step = cardWidth() || 1; return Math.round(scroller.scrollLeft / step); }
  function goToIndex(idx){ var step = cardWidth(); scroller.scrollTo({ left: idx * step, behavior: 'smooth' }); }
  function groupSize(){ return window.matchMedia('(min-width: 1024px)').matches ? Math.min(5, UNIQUE) : 1; }

  function jump(dir){
    var stepG = groupSize();
    var total = Math.max(UNIQUE * 2, 1);
    var idx   = currentIndex();
    var nextI = idx + dir * stepG;

    if (nextI < 0)      nextI += UNIQUE;
    if (nextI >= total) nextI -= UNIQUE;

    goToIndex(nextI);
    // krátky kopanec vetrom v smere pohybu
    induceKick(dir);
  }
  if (prev) prev.addEventListener('click', function(){ jump(-1); });
  if (next) next.addEventListener('click', function(){ jump( 1); });

  window.addEventListener('resize', function(){ goToIndex(currentIndex()); });

  /** Progressive lazy-loading */
  var imgs = Array.from(document.querySelectorAll('img.lazy-tshirt'));
  function loadOne(img){
    if (!img || img.dataset.loaded === '1') return;
    var src = img.getAttribute('data-src'); if (!src) return;
    img.src = src; img.dataset.loaded = '1'; img.classList.add('is-loaded'); img.removeAttribute('data-src');
  }
  if ('IntersectionObserver' in window) {
    var io = new IntersectionObserver(function(entries){
      entries.forEach(function(e){ if (e.isIntersecting){ loadOne(e.target); io.unobserve(e.target); } });
    }, { rootMargin: '300px 0px' });
    imgs.forEach(function(img){ io.observe(img); });
  } else { imgs.forEach(loadOne); }
  var queue = imgs.slice();
  var idle = window.requestIdleCallback || function(cb){ return setTimeout(cb, 300); };
  (function tick(){ idle(function(){ var n = queue.find(function(i){ return i && i.dataset && i.dataset.loaded !== '1'; }); if (n) loadOne(n); setTimeout(tick, 300); }); })();

  /** „Vietor“ — teraz aj pri scrollovaní carouselu a pri šípkach */
  (function swingWind(){
    var cards = Array.from(document.querySelectorAll('.tshirt-image-wrapper'));
    if (!cards.length) return;

    // per-card state
    var state = cards.map(function(el, i){
      return { el: el, angle: 0, target: 0, amp: 0.7 + (i % 5) * 0.08 };
    });

    var lastY = window.pageYOffset || 0;
    var lastLX = scroller.scrollLeft || 0;
    var ticking = false;
    var lastSetMs = 0;

    function applyTargets(base){
      lastSetMs = performance.now();
      state.forEach(function(s, idx){
        // rozstreliť mierne rozdielne
        var sign = (idx % 2 === 0 ? 1 : -1);
        s.target = base * s.amp * sign;
      });
      if (!ticking) { ticking = true; requestAnimationFrame(step); }
    }

    // Page scroll -> náklon podľa rýchlosti vertikálneho scrollu
    function onPageScroll(){
      var y = window.pageYOffset || 0;
      var delta = y - lastY; lastY = y;
      var base = Math.max(-10, Math.min(10, delta * 0.18)); // clamp
      applyTargets(base);
    }

    // Carousel scroll (drag/trackpad) -> náklon podľa horizontálneho pohybu
    function onRailScroll(){
      var x = scroller.scrollLeft || 0;
      var dx = x - lastLX; lastLX = x;
      var base = Math.max(-10, Math.min(10, dx * 0.09));   // smer doprava -> kladný uhol
      applyTargets(base);
    }

    // Krátky kopanec pri kliknutí na šípky
    window.induceKick = function(dir){
      // dir: -1 = doľava, 1 = doprava
      var base = 6 * (dir || 1);
      applyTargets(base);
    };

    function step(){
      var now = performance.now();
      var needMore = false;

      state.forEach(function(s){
        // smerom k cieľu
        var diff = s.target - s.angle;
        s.angle += diff * 0.12;
        // tlmenie targetu ak už chvíľu nič neprišlo
        if (now - lastSetMs > 120) s.target *= 0.86;

        var ry = s.angle;
        var ty = Math.sin(ry * Math.PI / 180) * 2;
        s.el.style.transform = 'rotate('+ry+'deg) translateY('+ty+'px)';

        if (Math.abs(s.angle) > 0.02 || Math.abs(s.target) > 0.02) needMore = true;
      });

      if (needMore) requestAnimationFrame(step); else ticking = false;
    }

    window.addEventListener('scroll', onPageScroll, { passive:true });
    scroller.addEventListener('scroll', onRailScroll, { passive:true });
  })();
});
</script>
