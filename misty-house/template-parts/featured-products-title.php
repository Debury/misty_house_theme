<?php
/**
 * Template part: Featured carousel title (light/dark)
 * Drop this above the carousel: get_template_part('template-parts/shop/featured-title');
 */
defined('ABSPATH') || exit;

$title  = get_theme_mod('misty_house_featured_title', __('From MistyHouse', 'misty-house'));
$scheme = get_query_var('mh_featured_title_scheme', 'light'); // 'light' | 'dark'
if (!$title) return;
?>
<section class="mh-featured-title-section <?php echo $scheme === 'dark' ? 'is-dark' : 'is-light'; ?>">
  <h2 class="mh-featured-title-clean"><?php echo esc_html($title); ?></h2>
</section>

<style>
/* Compact, white bg, NO bottom padding */
.mh-featured-title-section{
  width:100%;
  display:flex;
  justify-content:center;
  align-items:center;
  margin:0;
  padding-top:12px;        /* necháme vrch */
  padding-bottom:0;        /* spodok úplne preč */
  background:#fff;         /* čistá biela */
  box-shadow:none !important;
  filter:none !important;
}

/* žiadne umelé nafukovanie výšky */
.mh-featured-title-section{ min-height:0 !important; }

/* text */
.mh-featured-title-clean{
  margin:0;
  font-family:'Jockey One',sans-serif;
  font-weight:400;
  letter-spacing:.01em;
  line-height:1.02;
  color:#111;
  font-size:clamp(18px, 3.2vw, 2.5rem);
  text-shadow:none !important;
}

/* presne 2.5rem na väčších viewportoch (ak chceš fix) */
@media (min-width:1024px){
  .mh-featured-title-clean{ font-size:2.5rem; }
}

/* DARK variant (ak niekedy použiješ) */
.mh-featured-title-section.is-dark{ background:transparent; }
.mh-featured-title-section.is-dark .mh-featured-title-clean{
  color:#ffb700;
  text-shadow:0 1px 0 rgba(0,0,0,.45), 0 4px 14px rgba(255,183,0,.22);
}

/* Mobile – menší top padding, spodok stále 0 */
@media (max-width:640px){
  .mh-featured-title-section{
    padding-top:8px;
    padding-bottom:0;
  }
  .mh-featured-title-clean{
    font-size:clamp(16px, 6.5vw, 28px);
  }
}
</style>
