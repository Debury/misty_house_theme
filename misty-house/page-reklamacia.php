<?php
/**
 * Template Name: Page – Reklamačný poriadok
 * Description: Šablóna stránky s reklamačným poriadkom pre e-shop.
 */
defined('ABSPATH') || exit;
get_header();
?>

<style>
  /* --- Reklamačný poriadok (iba táto stránka) --- */
  .rp-page{max-width:900px;margin:0 auto;padding:40px 20px;color:#fff;line-height:1.65}
  .rp-page a{text-decoration:underline}
  .rp-page h1{margin:0 0 12px}
  .rp-page h2{margin:40px 0 12px}
  .rp-page ul{list-style:disc;list-style-position:outside;margin:.75rem 0 1rem;padding-left:1.5rem}
  .rp-page li{margin:.25rem 0}
  .rp-page .toc{border-top:1px solid rgba(255,255,255,.15);border-bottom:1px solid rgba(255,255,255,.15);padding:16px 0;margin:24px 0}
  .rp-page .toc ul{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;list-style:none;padding:0;margin:0}
  @media(min-width:1200px){.rp-page .toc ul{grid-template-columns:repeat(3,minmax(0,1fr))}}
  /* sticky header offset – desktop */
  @media(min-width:1024px){
    .rp-page{padding-top:140px}          /* uprav, ak máš vyšší/nižší header */
    .rp-page section{scroll-margin-top:140px}
  }
</style>

<main id="primary" class="site-main container rp-page">
  <header>
    <h1>Reklamačný poriadok</h1>
    <p style="opacity:.8;margin:0;">Naposledy aktualizované: <strong><!-- TODO: dátum --> {{DD. MM. RRRR}}</strong></p>
  </header>

  <nav class="toc" aria-label="Obsah">
    <ul>
      <li><a href="#vseobecne">I. Všeobecné ustanovenia</a></li>
      <li><a href="#odkazy">II. Odkazy</a></li>
      <li><a href="#zodpovednost">III. Zodpovednosť predávajúceho za vady</a></li>
      <li><a href="#zarucna">IV. Záručná doba</a></li>
      <li><a href="#postup">V. Postup pri uplatnení reklamácie</a></li>
      <li><a href="#prava">VI. Práva kupujúceho</a></li>
      <li><a href="#zaver">VII. Záverečné ustanovenia</a></li>
      <li><a href="#formular">Príloha: Reklamačný formulár</a></li>
    </ul>
  </nav>

  <section id="vseobecne">
    <h2>I. Všeobecné ustanovenia</h2>
    <p>Tento reklamačný poriadok je vydaný v súlade s Občianskym zákonníkom, zákonom o ochrane spotrebiteľa,
       zákonom č. 102/2014 Z. z. a zákonom o elektronickom obchode.</p>
    <p>
      Predávajúci: <strong><!-- TODO: obchodné meno/forma --> {{Michal Rengevič – MistyHouse}}</strong>,
      sídlo/miesto podnikania: <strong><!-- TODO: adresa --> {{Nábrežie Dr. Aurela Stodolu 1567/1, 031 01 Liptovský Mikuláš}}</strong>,
      IČO: <strong><!-- TODO --> {{53894022}}</strong>, DIČ: <strong><!-- TODO --> {{DOPLNIŤ}}</strong>,
      e-mail: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>, tel.: <a href="tel:+421917595666">+421 917 595 666</a>.
    </p>
    <p>Predávajúci prevádzkuje internetový obchod na <strong><!-- TODO: doména --> {{www.mistyhouse.sk}}</strong>.
       Reklamačný poriadok upravuje práva a povinnosti pri uplatňovaní práv z vád tovaru zakúpeného na diaľku.</p>
  </section>

  <section id="odkazy">
    <h2>II. Odkazy</h2>
    <p>Tento Reklamačný poriadok tvorí neoddeliteľnú súčasť <a href="<?php echo esc_url( home_url('/vop/') ); ?>">Všeobecných obchodných podmienok</a>.</p>
  </section>

  <section id="zodpovednost">
    <h2>III. Zodpovednosť predávajúceho za vady</h2>
    <ul>
      <li>Predávajúci je povinný dodať tovar v zhode so zmluvou: v požadovanej akosti, množstve a bez vád.</li>
      <li>Zodpovedá za vady existujúce pri prevzatí aj za vady, ktoré sa vyskytnú v záručnej dobe.</li>
      <li>Nezodpovedá za vady spôsobené neodborným zásahom, cudzími látkami/tekutinami, živelnými udalosťami alebo bežným opotrebením.</li>
    </ul>
  </section>

  <section id="zarucna">
    <h2>IV. Záručná doba</h2>
    <ul>
      <li>Záručná doba je <strong>24 mesiacov</strong> (pri použitej veci <strong>12 mesiacov</strong>), ak právny predpis neustanovuje inak.</li>
      <li>Začína plynúť dňom prevzatia tovaru Kupujúcim; pri výmene plynie nanovo od prevzatia nového tovaru.</li>
      <li>Čas od uplatnenia reklamácie po jej vybavenie sa do záručnej doby nezapočítava.</li>
    </ul>
  </section>

  <section id="postup">
    <h2>V. Postup pri uplatnení reklamácie</h2>
    <ul>
      <li>Reklamáciu uplatnite doručením tovaru Predávajúcemu na adresu:
          <strong><!-- TODO: adresa pre reklamácie/vrátenie --> {{Nábrežie Dr. A. Stodolu 1567/1, 031 01 Liptovský Mikuláš}}</strong>
          alebo osobne (ak je možnosť). Odporúčame priložiť doklad o kúpe a popis vady.</li>
      <li>Po prevzatí tovaru vystavíme potvrdenie o prijatí reklamácie.</li>
      <li>Spôsob vybavenia určíme bezodkladne, v zložitých prípadoch do 3 pracovných dní; vybavenie najneskôr do 30 dní.</li>
      <li>Ak bola reklamácia uplatnená do 12 mesiacov od kúpy, zamietnuť ju možno len na základe odborného posúdenia
          (kópiu posúdenia poskytneme do 14 dní od vybavenia).</li>
      <li>Po 12 mesiacoch – ak bude zamietnutá, v doklade uvedieme komu môžete tovar poslať na odborné posúdenie; náklady hradí Predávajúci,
          ak sa preukáže zodpovednosť Predávajúceho.</li>
      <li>Spotrebiteľ má právo na úhradu nevyhnutných nákladov (napr. poštovné) pri oprávnenej reklamácii.</li>
    </ul>

    <p id="formular" style="margin-top:12px">
      <strong>Reklamačný formulár (na stiahnutie):</strong>
      <!-- TODO: vlož URL na nahratý súbor PDF/DOCX v médiách -->
      <a href="{{http://localhost:8080/wp-content/uploads/2025/08/reklamacny-formular.pdf}}" target="_blank" rel="noopener">Stiahnuť formulár</a>
    </p>
  </section>

  <section id="prava">
    <h2>VI. Práva kupujúceho pri vadách</h2>
    <ul>
      <li>Odstrániteľná vada: právo na bezplatné, včasné a riadne odstránenie; prípadne výmenu tovaru/súčasti.</li>
      <li>Neodstrániteľná vada brániaca užívaniu: právo na výmenu alebo odstúpenie od zmluvy.</li>
      <li>Iné neodstrániteľné vady: právo na primeranú zľavu z ceny.</li>
      <li>Pri tovare za nižšiu cenu alebo použitom – právo na primeranú zľavu.</li>
    </ul>
  </section>

  <section id="zaver">
    <h2>VII. Záverečné ustanovenia</h2>
    <p>Tento Reklamačný poriadok je platný a účinný dňom
      <strong><!-- TODO: dátum účinnosti --> {{DD. MM. RRRR}}</strong> a je zverejnený v Internetovom obchode Predávajúceho.</p>
  </section>

  <hr class="sep" style="border-color:rgba(255,255,255,.15);margin:24px 0" />
  <p style="opacity:.8">Kontakt: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>, tel. <a href="tel:+421917595666">+421 917 595 666</a></p>
</main>

<?php get_footer(); ?>
