<?php
/**
 * Template Name: Page – GDPR (Ochrana osobných údajov)
 * Description: Statická stránka so zásadami spracúvania osobných údajov pre MistyHouse.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main id="primary" class="site-main container gdpr-page" style="max-width:900px;margin:0 auto;padding:40px 20px;color:#fff;">
  <header style="margin-bottom:24px;">
    <h1 style="margin:0 0 8px;">Ochrana osobných údajov (GDPR)</h1>
    <p style="opacity:.8;margin:0;">Naposledy aktualizované: <?php echo esc_html( date_i18n( get_option('date_format') ) ); ?></p>
  </header>

  <nav aria-label="Obsah" style="border-top:1px solid rgba(255,255,255,.15);border-bottom:1px solid rgba(255,255,255,.15);padding:16px 0;margin:24px 0;">
    <ul style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;list-style:none;padding:0;margin:0;">
      <li><a href="#prevadzkovatel">Prevádzkovateľ</a></li>
      <li><a href="#zodpovedna-osoba">Zodpovedná osoba</a></li>
      <li><a href="#rozsah-udajov">Aké údaje spracúvame</a></li>
      <li><a href="#ucely">Účely a právne základy</a></li>
      <li><a href="#prijemcovia">Príjemcovia údajov</a></li>
      <li><a href="#prenos">Prenos do tretích krajín</a></li>
      <li><a href="#doba">Doba uchovávania</a></li>
      <li><a href="#prava">Práva dotknutej osoby</a></li>
      <li><a href="#odvolanie">Odvolanie súhlasu</a></li>
      <li><a href="#staznost">Sťažnosť dozornému orgánu</a></li>
      <li><a href="#povinnost">Povinnosť poskytnúť údaje</a></li>
      <li><a href="#automatizovane">Automatizované rozhodovanie</a></li>
    </ul>
  </nav>

  <section id="prevadzkovatel">
    <h2>Prevádzkovateľ</h2>
    <p>
      <strong>Michal Rengevič – MistyHouse</strong><br>
      Nábrežie Dr. Aurela Stodolu 1567/1, 031&nbsp;01 Liptovský Mikuláš<br>
      IČO: 53&nbsp;894&nbsp;022<br>
      E-mail: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a><br>
      Telefón: <a href="tel:+421917595666">+421&nbsp;917&nbsp;595&nbsp;666</a>
    </p>
  </section>

  <section id="zodpovedna-osoba">
    <h2>Zodpovedná osoba</h2>
    <p>Zodpovedná osoba nie je ustanovená.</p>
  </section>

  <section id="rozsah-udajov">
    <h2>Aké osobné údaje spracúvame</h2>
    <p>V závislosti od situácie spracúvame najmä tieto kategórie údajov:</p>
    <ul>
      <li>identifikačné a kontaktné údaje: meno, priezvisko, bydlisko, dodacia adresa, telefón, e-mail, IP adresa, cookies, podpis,</li>
      <li>platobné údaje: číslo účtu, názov banky (ak je potrebné na vrátenie platby či fakturáciu).</li>
    </ul>
  </section>

  <section id="ucely">
    <h2>Účely a právne základy spracúvania</h2>
    <p>Údaje spracúvame na tieto účely:</p>
    <ul>
      <li><strong>Plnenie zmluvy a predzmluvné úkony</strong> (objednávka, doručenie, reklamácie) – právny základ: čl. 6 ods. 1 písm. b) GDPR,</li>
      <li><strong>Účtovníctvo a daňové povinnosti, archivácia</strong> – právny základ: čl. 6 ods. 1 písm. c) GDPR,</li>
      <li><strong>Evidencia klientov, korešpondencia a komunikácia</strong> – právny základ: čl. 6 ods. 1 písm. b) alebo f) GDPR (oprávnený záujem na efektívnej komunikácii),</li>
      <li><strong>Cookies a analytika</strong> – právny základ: súhlas podľa čl. 6 ods. 1 písm. a) GDPR (ak sa vyžaduje).</li>
    </ul>
  </section>

  <section id="prijemcovia">
    <h2>Príjemcovia alebo kategórie príjemcov</h2>
    <p>Údaje môžu byť sprístupnené najmä týmto príjemcom (len v nevyhnutnom rozsahu):</p>
    <ul>
      <li>účtovná spoločnosť, poskytovatelia právnych služieb, IT správa a hosting,</li>
      <li>prepravcovia a doručovacie služby (napr. Slovenská pošta),</li>
      <li>štátne orgány (súdy, OČTK, daňový úrad) v prípadoch podľa zákona.</li>
    </ul>
  </section>

  <section id="prenos">
    <h2>Prenos do tretích krajín</h2>
    <p>Prenos osobných údajov do tretích krajín sa <strong>neuskutočňuje</strong>.</p>
  </section>

  <section id="doba">
    <h2>Doba uchovávania</h2>
    <p>Údaje uchovávame po nevyhnutný čas na splnenie účelu (plnenie zmluvy), a následne po dobu vyžadovanú osobitnými predpismi (účtovné a archivačné lehoty) alebo na preukazovanie právnych nárokov.</p>
  </section>

  <section id="prava">
    <h2>Práva dotknutej osoby</h2>
    <p>V súlade s GDPR máte najmä právo:</p>
    <ul>
      <li>na prístup k údajom (čl. 15),</li>
      <li>na opravu nepresných údajov (čl. 16),</li>
      <li>na vymazanie – „právo na zabudnutie“ (čl. 17),</li>
      <li>na obmedzenie spracúvania (čl. 18),</li>
      <li>na prenosnosť údajov (čl. 20),</li>
      <li>namietať spracúvanie vrátane priameho marketingu (čl. 21),</li>
      <li>na to, aby sa na vás nevzťahovalo rozhodnutie založené výlučne na automatizovanom spracúvaní vrátane profilovania (čl. 22).</li>
    </ul>
    <p>Práva si môžete uplatniť e-mailom na <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a> alebo poštou na adresu prevádzkovateľa.</p>
  </section>

  <section id="odvolanie">
    <h2>Odvolanie súhlasu</h2>
    <p>Ak spracúvanie prebieha na základe súhlasu, môžete ho kedykoľvek odvolať (celý alebo čiastočne) bez vplyvu na zákonnosť spracúvania pred odvolaním.</p>
  </section>

  <section id="staznost">
    <h2>Sťažnosť dozornému orgánu</h2>
    <p>Máte právo podať sťažnosť na Úrad na ochranu osobných údajov SR, Hraničná 12, 820&nbsp;07 Bratislava, e-mail: <a href="mailto:statny.dozor@pdp.gov.sk">statny.dozor@pdp.gov.sk</a>, tel.: 02/3231&nbsp;3214.</p>
  </section>

  <section id="povinnost">
    <h2>Povinnosť poskytnúť údaje</h2>
    <p>Poskytnutie údajov je potrebné na uzatvorenie a plnenie zmluvy. Ak údaje neposkytnete, nebudeme vedieť objednávku uzatvoriť ani doručiť.</p>
  </section>

  <section id="automatizovane">
    <h2>Automatizované rozhodovanie</h2>
    <p>Automatizované individuálne rozhodovanie ani profilovanie <strong>nevykonávame</strong>.</p>
  </section>

  <footer style="margin-top:32px;border-top:1px solid rgba(255,255,255,.15);padding-top:16px;opacity:.8;">
    <p>Ak máte otázky k spracúvaniu údajov, kontaktujte nás na <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>.</p>
  </footer>
</main>

<?php get_footer(); ?>
