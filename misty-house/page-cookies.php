<?php
/**
 * Template Name: Page – Cookies
 * Description: Stránka so zásadami používania súborov cookies (stručná verzia).
 */
defined('ABSPATH') || exit;
get_header();
?>
<style>
  .cookies-page{max-width:900px;margin:0 auto;padding:40px 20px;color:#fff;line-height:1.65}
  .cookies-page a{text-decoration:underline}
  .cookies-page h1{margin:0 0 12px}
  .cookies-page h2{margin:32px 0 10px}
  .cookies-page h3{margin:20px 0 8px}
  .cookies-page ul{list-style:disc;list-style-position:outside;margin:.5rem 0 1rem;padding-left:1.5rem}
  .cookies-page li{margin:.25rem 0}
  .cookies-page code{background:rgba(255,255,255,.08);padding:.05rem .35rem;border-radius:4px}
  .cookies-page .toc{border-top:1px solid rgba(255,255,255,.15);border-bottom:1px solid rgba(255,255,255,.15);padding:16px 0;margin:24px 0}
  .cookies-page .toc ul{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;list-style:none;padding:0;margin:0}
  .cookies-page .muted{opacity:.8}
  @media(min-width:1024px){.cookies-page{padding-top:140px}.cookies-page section{scroll-margin-top:140px}}
</style>

<main id="primary" class="site-main container cookies-page">
  <header>
    <h1>Cookies</h1>
    <p class="muted" style="margin:0;">
      Naposledy aktualizované:
      <strong><?php echo esc_html( date_i18n( get_option('date_format') ) ); ?></strong>
    </p>
  </header>

  <nav class="toc" aria-label="Obsah">
    <ul>
      <li><a href="#co-su">Čo sú cookies</a></li>
      <li><a href="#ako-pouzivame">Ako cookies používame</a></li>
      <li><a href="#typy">Typy cookies na našom webe</a></li>
      <li><a href="#sprava">Správa a zmena súhlasu</a></li>
      <li><a href="#detaily">Zoznam použitých cookies</a></li>
      <li><a href="#kontakt">Kontakt</a></li>
    </ul>
  </nav>

  <section id="co-su">
    <h2>Čo sú cookies</h2>
    <p>Cookies sú malé textové súbory ukladané vo vašom prehliadači. Pomáhajú správne fungovať stránke (napr. košík, prihlásenie) a
       môžu slúžiť aj na analytiku alebo marketing – tie sa u nás načítajú iba na základe vášho súhlasu.</p>
  </section>

  <section id="ako-pouzivame">
    <h2>Ako cookies používame</h2>
    <p>
      Na webe používame najmä <strong>nevyhnutné</strong> cookies potrebné na prevádzku e-shopu
      (košík, pokladňa, prihlásenie) a na bezpečnosť/performance (Cloudflare).
      Analytické cookies <strong>používame len po vašom súhlase</strong>.
    </p>
  </section>

  <section id="typy">
    <h2>Typy cookies na našom webe</h2>
    <ul>
      <li><strong>Nevyhnutné (funkčné)</strong> – základné funkcie e-shopu (košík, checkout, bezpečnosť). Bez nich stránka nefunguje správne.</li>
      <li><strong>Preferenčné</strong> – zapamätanie si vašich volieb (napr. jazyk), ak sú aktívne.</li>
      <li><strong>Analytické</strong> – meranie návštevnosti a výkonu (použijú sa len so súhlasom).</li>
      <li><strong>Marketingové</strong> – personalizácia reklám (aktuálne nepoužívame).</li>
    </ul>
  </section>

  <section id="sprava">
    <h2>Správa a zmena súhlasu</h2>
    <p>
      Cookies môžete spravovať vo svojom prehliadači (blokovať/mazať). Ak používame banner so súhlasom,
      môžete ho kedykoľvek zmeniť cez tento odkaz:
      <!-- CookieYes: odkaz na otvorenie nastavení (funguje s ich skriptom) -->
      <a href="#" class="js-open-cookie-settings" data-cky-tag="settings">Nastavenia cookies</a>.
    </p>
  </section>

  <section id="detaily">
    <h2>Zoznam použitých cookies</h2>

    <h3>Nevyhnutné (WordPress / WooCommerce / Consent)</h3>
    <ul>
      <li>
        <code>cookieyes-consent</code> (doména: mistyhouse.sk, platnosť: 1 rok) – CookieYes nastavuje toto cookie na zapamätanie
        preferencií súhlasu, aby boli rešpektované pri ďalších návštevách. Nezbiera osobné údaje.
      </li>
      <li>
        <code>wordpress_test_cookie</code> (doména: mistyhouse.sk, relácia) – WordPress používa na overenie, či má prehliadač povolené cookies.
      </li>
      <li><code>woocommerce_cart_hash</code>, <code>woocommerce_items_in_cart</code> – pomocné cookies pre košík (relácia).</li>
      <li><code>wp_woocommerce_session_*</code> – anonymný identifikátor košíka (2 dni).</li>
      <li><code>wordpress_logged_in_*</code>, <code>wordpress_sec_*</code> – prihlásenie používateľa (relácia).</li>
      <li><code>wp-settings-*</code>, <code>wp-settings-time-*</code> – nastavenia administrátora (len pre prihlásených).</li>
    </ul>

    <h3>Bezpečnosť / výkon (Cloudflare)</h3>
    <ul>
      <li><code>__cf_bm</code> – ochrana pred botmi (krátka relácia).</li>
      <li><code>cf_clearance</code> – dočasný token na prechod cez ochranu, ak je potrebné (dočasné).</li>
    </ul>

    <h3>Analytické (Sourcebuster – len po súhlase)</h3>
    <ul>
      <li><code>sbjs_migrations</code> (doména: .mistyhouse.sk, relácia) – identifikácia zdroja návštevy a akcií.</li>
      <li><code>sbjs_current_add</code> (doména: .mistyhouse.sk, relácia) – identifikácia aktuálneho zdroja návštevy.</li>
      <li><code>sbjs_first_add</code> (doména: .mistyhouse.sk, relácia) – prvotný zdroj návštevy.</li>
      <li><code>sbjs_current</code> (doména: .mistyhouse.sk, relácia) – aktuálny zdroj/parametre kampane.</li>
      <li><code>sbjs_first</code> (doména: .mistyhouse.sk, relácia) – prvý zdroj/parametre kampane.</li>
      <li><code>sbjs_udata</code> (doména: .mistyhouse.sk, relácia) – pomocné údaje k zdroju návštevy.</li>
      <li><code>sbjs_session</code> (doména: .mistyhouse.sk, 1 hodina) – identifikátor analytickej relácie.</li>
    </ul>

    <h3>Moduly tretích strán (len ak sú aktívne)</h3>
    <ul>
      <li><strong>Packeta / platobná brána</strong> – môžu nastaviť vlastné funkčné cookies potrebné na spracovanie služby.</li>
    </ul>
  </section>

  <section id="kontakt">
    <h2>Kontakt</h2>
    <p>
      Prevádzkovateľ: <strong>Michal Rengevič – MistyHouse</strong><br>
      E-mail: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>,
      tel.: <a href="tel:+421917595666">+421 917 595 666</a>
    </p>
  </section>
</main>

<script>
  // Ak je k dispozícii CookieYes, nech odkaz otvorí ich nastavenia
  (function(){
    var link = document.querySelector('.js-open-cookie-settings');
    if (!link) return;
    link.addEventListener('click', function(e){
      e.preventDefault();
      try {
        if (window.cookieyes && typeof window.cookieyes.openSettings === 'function') {
          window.cookieyes.openSettings();
        } else {
          // fallback: ak CookieYes ešte nenačítaný, pokús sa spustiť neskôr
          var i = 0, timer = setInterval(function(){
            if (window.cookieyes && typeof window.cookieyes.openSettings === 'function') {
              clearInterval(timer); window.cookieyes.openSettings();
            }
            if (++i > 20) clearInterval(timer);
          }, 250);
        }
      } catch (err) { console.warn('CookieYes settings not available:', err); }
    });
  })();
</script>

<?php get_footer(); ?>
