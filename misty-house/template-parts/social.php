<?php
/**
 * Template part for displaying social mozaic section
 *
 * @package Misty_House
 */

$placeholders = [
    'Rectangle 28.png',
    'Rectangle 29.png',
    'Rectangle 30.png',
    'Rectangle 28-1.png',
    'Rectangle 29-1.png',
];
?>

<section class="social-section" id="social">
  <div class="social-grid-desktop">
    <?php for ( $i = 1; $i <= 11; $i++ ) :
      $def     = $placeholders[ ( $i - 1 ) % count( $placeholders ) ];
      $def_url = get_template_directory_uri() . "/assets/images/$def";
      $alt     = get_theme_mod( "misty_house_mozaic_alt_$i", sprintf( __( 'Mozaic Image %d', 'misty-house' ), $i ) );
      $img     = get_theme_mod( "misty_house_mozaic_image_$i", $def_url );
      $link    = get_theme_mod( "misty_house_mozaic_link_$i", '#' );
    ?>
      <a href="<?php echo esc_url( $link ); ?>"
         class="social-item"
         target="_blank"
         rel="noopener noreferrer">
        <img
          src="<?php echo esc_url( $img ); ?>"
          alt="<?php echo esc_attr( $alt ); ?>"
          class="social-icon"
          loading="lazy"
        >
      </a>
    <?php endfor; ?>
  </div>

  <div class="social-mobile-grid">
    <?php for ( $i = 1; $i <= 11; $i++ ) :
      $def     = $placeholders[ ( $i - 1 ) % count( $placeholders ) ];
      $def_url = get_template_directory_uri() . "/assets/images/$def";
      $alt     = get_theme_mod( "misty_house_mozaic_alt_$i", sprintf( __( 'Mozaic Image %d', 'misty-house' ), $i ) );
      $img     = get_theme_mod( "misty_house_mozaic_image_$i", $def_url );
      $link    = get_theme_mod( "misty_house_mozaic_link_$i", '#' );
    ?>
      <a href="<?php echo esc_url( $link ); ?>"
         class="social-mobile-item"
         target="_blank"
         rel="noopener noreferrer">
        <img
          src="<?php echo esc_url( $img ); ?>"
          alt="<?php echo esc_attr( $alt ); ?>"
          loading="lazy"
        >
      </a>
    <?php endfor; ?>
  </div>
</section>


<script>
(function(){
  const mq   = window.matchMedia('(max-width: 768px)');
  const sec  = document.querySelector('.social-section');
  const grid = document.querySelector('.social-section > .social-grid-desktop');
  if (!sec || !grid) return;

  // ===== Nastav rýchlosť tu (px za sekundu) =====
  let PX_PER_SEC = 18;        // napr. 12 = pomalšie, 30 = rýchlejšie

  let dir = 1;                // 1 = doprava, -1 = doľava
  let paused = false;
  let raf = null;
  let resumeTO;
  let frac = 0;               // akumulátor zlomkov pixela
  let last = 0;               // čas posledného frame

  function maxScroll(){
    return Math.max(0, grid.scrollWidth - sec.clientWidth);
  }

  function step(now){
    if (!last) last = now;
    const dt = (now - last) / 1000;   // s
    last = now;

    if (paused || !mq.matches) { raf = null; return; }

    const max = maxScroll();
    if (max <= 0) { raf = null; return; }

    // časovo riadený posun (plynulý aj pri malých rýchlostiach)
    frac += dir * PX_PER_SEC * dt;

    if (Math.abs(frac) >= 1){
      const delta = frac > 0 ? Math.floor(frac) : Math.ceil(frac);
      let next = sec.scrollLeft + delta;

      if (next <= 0){ next = 0; dir = 1; frac = 0; }
      else if (next >= max){ next = max; dir = -1; frac = 0; }

      sec.scrollLeft = next;
      frac -= delta;
    }

    raf = requestAnimationFrame(step);
  }

  function start(){
    if (!mq.matches) { stop(); return; }
    if (raf == null){
      last = 0;                // reset time
      raf = requestAnimationFrame(step);
    }
  }

  function stop(){
    if (raf != null){ cancelAnimationFrame(raf); raf = null; }
  }

  function pause(){
    paused = true;
    if (resumeTO) clearTimeout(resumeTO);
    sec.classList.add('is-paused');     // -> CSS povolí scroll
    stop();
  }

  function resumeSoon(ms=2500){
    if (resumeTO) clearTimeout(resumeTO);
    resumeTO = setTimeout(()=>{
      paused = false;
      sec.classList.remove('is-paused');
      start();
    }, ms);
  }

  // Interakcie: dotyk/koliesko pauzujú, po chvíli sa obnoví
  ['pointerdown','touchstart','wheel'].forEach(ev =>
    sec.addEventListener(ev, pause, {passive:true})
  );
  ['pointerup','touchend','mouseleave'].forEach(ev =>
    sec.addEventListener(ev, ()=>resumeSoon(3000), {passive:true})
  );
  // počas ručného scrollu drž pauzu a po chvíli obnov
  sec.addEventListener('scroll', ()=>{ if (paused) resumeSoon(2200); }, {passive:true});

  // Reflow / zmena break-pointu
  let rz;
  function onResize(){
    if (rz) clearTimeout(rz);
    rz = setTimeout(()=>{
      const max = maxScroll();
      if (sec.scrollLeft > max) sec.scrollLeft = max;
      if (!paused) start();
    }, 150);
  }
  window.addEventListener('resize', onResize);
  (mq.addEventListener || mq.addListener).call(mq, 'change', ()=>{ paused=false; start(); });

  // Štart
  start();
})();
</script>
