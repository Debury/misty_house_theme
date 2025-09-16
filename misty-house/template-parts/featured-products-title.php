<?php
/**
 * Template part: Featured carousel title (light/dark)
 * Drop above carousel: get_template_part('template-parts/shop/featured-title');
 */
defined('ABSPATH') || exit;

$title  = get_theme_mod('misty_house_featured_title', __('From MistyHouse', 'misty-house'));
$scheme = get_query_var('mh_featured_title_scheme', 'dark'); // 'light' | 'dark'
if (!$title) return;
?>
<section class="mh-featured-title-section <?php echo $scheme === 'dark' ? 'is-dark' : 'is-light'; ?>">
  <h2 class="mh-featured-title-clean"><?php echo esc_html($title); ?></h2>
</section>

<style>
/* Base: center, no shadows, compact */
.mh-featured-title-section{
  display:flex;
  justify-content:center;
  align-items:center;
  width:100%;
  margin:0;
  padding-top:28px;       /* priestor nad textom */
  padding-bottom:16px;    /* a aj pod textom (viditeľné na čiernom) */
  border:0;
  outline:0;
  box-shadow:none !important;
  filter:none !important;
  min-height:0 !important;
}

/* DARK: čierne pozadie + zlatý text */
.mh-featured-title-section.is-dark{ background:#000; }
.mh-featured-title-section.is-dark .mh-featured-title-clean{
  color:#ffb700;
  text-shadow:0 1px 0 rgba(0,0,0,.45), 0 4px 14px rgba(255,183,0,.22);
}

/* LIGHT: biele pozadie + čierny text */
.mh-featured-title-section.is-light{ background:#fff; }
.mh-featured-title-section.is-light .mh-featured-title-clean{
  color:#111;
  text-shadow:none;
}

/* Text */
.mh-featured-title-clean{
  margin:0;
  text-align:center;
  font-family:'Jockey One',sans-serif;
  font-weight:400;
  letter-spacing:.01em;
  line-height:1.02;
  font-size:clamp(18px,3.2vw,2.5rem);
}

/* Mobile: jemne menší spacing */
@media (max-width:640px){
  .mh-featured-title-section{ padding-top:20px; padding-bottom:12px; }
  .mh-featured-title-clean{ font-size:clamp(16px,6.5vw,28px); }
}
</style>
