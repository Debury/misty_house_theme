<?php
/**
 * Template part: Featured carousel title (ALWAYS DARK)
 * Drop above the carousel: get_template_part('template-parts/shop/featured-title');
 */
defined('ABSPATH') || exit;

$title = get_theme_mod('misty_house_featured_title', __('From MistyHouse', 'misty-house'));
if (!$title) return;
?>
<section class="mh-featured-title-section">
  <h2 class="mh-featured-title-clean"><?php echo esc_html($title); ?></h2>
</section>

<style>
/* Always DARK: black bg + gold text (forced) */
.mh-featured-title-section{
  display:flex;
  justify-content:center;
  align-items:center;
  width:100%;
  margin:0;
  padding-top:28px;          /* priestor nad textom */
  padding-bottom:16px;       /* a aj pod textom */
  border:0;
  outline:0;
  box-shadow:none !important;
  filter:none !important;
  min-height:0 !important;
  background:#000 !important;          /* FORCE dark */
}

.mh-featured-title-section .mh-featured-title-clean{
  margin:0;
  text-align:center;
  font-family:'Jockey One',sans-serif;
  font-weight:400;
  letter-spacing:.01em;
  line-height:1.02;
  font-size:clamp(18px,3.2vw,2.5rem);
  color:#ffb700 !important;            /* FORCE gold */
  text-shadow:0 1px 0 rgba(0,0,0,.45), 0 4px 14px rgba(255,183,0,.22) !important;
}

/* Mobile: jemne menší spacing & text */
@media (max-width:640px){
  .mh-featured-title-section{
    padding-top:20px;
    padding-bottom:12px;
  }
  .mh-featured-title-section .mh-featured-title-clean{
    font-size:clamp(16px,6.5vw,28px);
  }
}
</style>
