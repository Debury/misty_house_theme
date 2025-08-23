<?php
/**
 * Template Name: Page – VOP (Všeobecné obchodné podmienky)
 * Description: VOP šablóna s obsahom a kotvami, pripravená pre e-shop.
 */

defined('ABSPATH') || exit;

get_header();
?>

<style>
  /* --- VOP page only --- */
  .vop-page { max-width: 900px; margin: 0 auto; padding: 40px 20px; color: #fff; }
  .vop-page a { text-decoration: underline; }
  .vop-page h1 { margin: 0 0 12px; }
  .vop-page h2 { margin-top: 40px; margin-bottom: 12px; }
  .vop-page p, .vop-page li { line-height: 1.65; }
  .vop-page .toc {
    border-top: 1px solid rgba(255,255,255,.15);
    border-bottom: 1px solid rgba(255,255,255,.15);
    padding: 16px 0; margin: 24px 0;
  }
  .vop-page .toc ul {
    display: grid; grid-template-columns: repeat(2,minmax(0,1fr));
    gap: 8px; list-style: none; padding: 0; margin: 0;
  }
  .vop-page .sep { border-top: 1px solid rgba(255,255,255,.15); margin: 24px 0; }

  /* fix pre sticky header na desktope – nech sa nadpis “nezasunie” pod navbar */
  @media (min-width: 1024px) {
    .vop-page { padding-top: clamp(40px, 8vw, 96px); }
  }
</style>

<main id="primary" class="site-main container vop-page">
  <header>
    <h1>Všeobecné obchodné podmienky</h1>
    <p style="opacity:.8;margin:0;">Naposledy aktualizované: <!-- TODO: dátum --> <strong>{{23. 8. 2025}}</strong></p>
  </header>

  <nav class="toc" aria-label="Obsah">
    <ul>
      <li><a href="#uvod">I. Úvodné ustanovenia</a></li>
      <li><a href="#objednavka">II. Objednávka a zmluva</a></li>
      <li><a href="#cena">III. Cena a platby</a></li>
      <li><a href="#platby">IV. Spôsoby platby</a></li>
      <li><a href="#dodanie">V. Dodanie tovaru</a></li>
      <li><a href="#prevzatie">VI. Prevzatie tovaru</a></li>
      <li><a href="#doprava">VII. Doprava a poštovné</a></li>
      <li><a href="#odstupenie">VIII. Odstúpenie od zmluvy</a></li>
      <li><a href="#ars">IX. Alternatívne riešenie sporov</a></li>
      <li><a href="#zaver">X. Záverečné ustanovenia</a></li>
    </ul>
  </nav>

  <section id="uvod">
    <h2>I. Úvodné ustanovenia a vymedzenie pojmov</h2>
    <p>
      Tieto Všeobecné obchodné podmienky (ďalej len „VOP“) upravujú právne vzťahy medzi
      <strong><!-- TODO: Obchodné meno + forma --> {{Michal Rengevič – MistyHouse}}</strong>,
      s miestom podnikania <strong><!-- TODO: adresa --> {{Nábrežie Dr. Aurela Stodolu 1567/1, 031 01 Liptovský Mikuláš}}</strong>,
      IČO: <strong><!-- TODO: IČO --> {{53894022}}</strong>,
      DIČ: <strong><!-- TODO: DIČ --> {{DOPLNIŤ}}</strong>,
      e-mail: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>, tel.: <a href="tel:+421917595666">+421 917 595 666</a>
      (ďalej len „Predávajúci“), a každou osobou, ktorá si objedná tovar cez internetový obchod na adrese
      <strong><!-- TODO: doména --> {{www.mistyhouse.sk}}</strong> (ďalej len „Internetový obchod“).
    </p>
    <p>
      Kupujúcim je každá osoba, ktorá vyplní a odošle objednávku a ktorej Predávajúci potvrdí prijatie objednávky.
      Spotrebiteľom je fyzická osoba, ktorá nekoná v rámci predmetu svojej podnikateľskej činnosti.
    </p>
  </section>

  <section id="objednavka">
    <h2>II. Objednávka produktu – uzatvorenie kúpnej zmluvy</h2>
    <ul>
      <li>Návrhom na uzavretie zmluvy je objednávka vytvorená cez formulár v Internetovom obchode.</li>
      <li>K uzavretiu zmluvy dochádza okamihom doručenia potvrdenia objednávky zo strany Predávajúceho.</li>
      <li>Zmluva sa uzatvára na dobu určitú a zaniká splnením záväzkov strán; môže zaniknúť aj dohodou alebo odstúpením.</li>
      <li>Vlastnosti tovaru sú oznámené ešte pred uzavretím zmluvy v detaile produktu.</li>
    </ul>
  </section>

  <section id="cena">
    <h2>III. Kúpna cena a platobné podmienky</h2>
    <p>
      Kúpna cena je uvedená pri každom produkte v čase vytvorenia objednávky.
      Je konečná (Predávajúci <!-- TODO: vyber jednu možnosť --> <strong>{{je / nie je}}</strong> platiteľom DPH).
      Ku kúpnej cene sa pripočítava poštovné/doprava podľa čl. VII a prípadné poplatky za zvolený spôsob platby podľa čl. IV.
      Základnou menou je euro (EUR).
    </p>
  </section>

  <section id="platby">
    <h2>IV. Spôsoby platby</h2>
    <ul>
      <li><strong>Dobierka</strong> – poplatok: <strong><!-- TODO: doplň sumu alebo „0 €“ --> {{… €}}</strong>.</li>
      <li><strong>Bankový prevod</strong> (na základe faktúry) – poplatok: <strong>0 €</strong>.</li>
      <li><!-- TODO: voliteľné --> <strong>Karta / online platba</strong> – poskytovateľ: <strong>{{…}}</strong>, poplatok: <strong>{{… €}}</strong>.</li>
    </ul>
  </section>

  <section id="dodanie">
    <h2>V. Dodanie tovaru</h2>
    <ul>
      <li>Pri platbe na dobierku: odoslanie do <strong><!-- TODO --> {{3}}</strong> pracovných dní od uzavretia zmluvy; doručenie do <strong>{{6}}</strong> pracovných dní.</li>
      <li>Pri platbe prevodom: odoslanie do <strong>{{3}}</strong> dní od pripísania platby; doručenie do <strong>{{6}}</strong> dní od pripísania.</li>
      <li>Miestom dodania je adresa uvedená Kupujúcim, alebo osobný odber v sídle Predávajúceho (ak je ponúkaný).</li>
    </ul>
  </section>

  <section id="prevzatie">
    <h2>VI. Prevzatie tovaru</h2>
    <ul>
      <li>Nebezpečenstvo škody prechádza na Kupujúceho prevzatím tovaru (osobne alebo poverenou osobou).</li>
      <li>Kupujúci má právo neprevziať tovar najmä ak je poškodený, neúplný alebo nezodpovedá zmluve.</li>
    </ul>
  </section>

  <section id="doprava">
    <h2>VII. Prepravné – spôsoby dopravy a ceny</h2>
    <p>Prepravné nie je súčasťou ceny produktu; zobrazí sa pred uzavretím zmluvy. Kupujúci si vyberá z možností:</p>
    <ul>
      <li><strong>Osobný odber</strong> – poplatok: <strong>0 €</strong>. Miesto: <!-- TODO: ak ponúkaš --> <strong>{{…}}</strong>.</li>
      <li><strong>Slovenská pošta</strong> – poplatok: <strong><!-- TODO --> {{… €}}</strong>.</li>
      <li><strong>Packeta/Kuriér</strong> – poplatok: <strong><!-- TODO --> {{… €}}</strong>.</li>
    </ul>
  </section>

  <section id="odstupenie">
    <h2>VIII. Odstúpenie spotrebiteľa od zmluvy bez dôvodu</h2>
    <ul>
      <li>Spotrebiteľ môže odstúpiť do 14 kalendárnych dní od prevzatia tovaru (alebo aj pred doručením).</li>
      <li>Odstúpenie pošli na adresu Predávajúceho: <strong><!-- TODO: potvrdzovacia adresa --> {{Nábrežie Dr. A. Stodolu 1567/1, 031 01 Liptovský Mikuláš}}</strong>
          alebo e-mailom na <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>.</li>
      <li>Tovar pošli späť do 14 dní od odstúpenia; náklady na vrátenie znáša spotrebiteľ (ak sa nedohodne inak).</li>
      <li>Predávajúci vráti všetky prijaté platby vrátane najlacnejšieho bežného poštovného do 14 dní od doručenia odstúpenia
          (môže zadržať platbu do doručenia tovaru alebo preukázania odoslania).</li>
      <li>Na stránke je dostupný <strong>Formulár na odstúpenie od zmluvy</strong> (na stiahnutie) – odporúčame pridať odkaz tu:
        <!-- TODO: vlož URL na .docx alebo PDF -->
        <a href="{{http://localhost:8080/wp-content/uploads/2025/08/www.mistyhouse.sk_formular-na-odstupenie-od-zmluvy-pre-sporebitela-1.docx}}">Stiahnuť formulár</a>.
      </li>
      <li>Výnimky z práva odstúpiť (napr. hygienicky zapečatený tovar po porušení obalu, tovar vyrobený na mieru, atď.).</li>
    </ul>
  </section>

  <section id="ars">
    <h2>IX. Alternatívne riešenie sporov</h2>
    <p>
      Ak nie si spokojný so spôsobom vybavenia reklamácie, môžeš sa obrátiť na Predávajúceho so žiadosťou o nápravu.
      Ak ti odpovieme zamietavo alebo do 30 dní neodpovieme, môžeš podať návrh na začatie alternatívneho riešenia sporu (ARS)
      napr. Slovenskej obchodnej inšpekcii alebo inému oprávnenému subjektu (zoznam na <a href="https://www.mhsr.sk">www.mhsr.sk</a>).
      Platforma ODR: <a href="https://ec.europa.eu/consumers/odr">ec.europa.eu/consumers/odr</a>.
    </p>
  </section>

  <section id="zaver">
    <h2>X. Záverečné ustanovenia</h2>
    <p>
      Na vzťahy so spotrebiteľom sa použije Občiansky zákonník a zákon č. 102/2014 Z. z.; na podnikateľov Obchodný zákonník.
      Neoddeliteľnou súčasťou týchto VOP je <strong>Reklamačný poriadok</strong>.
      Tieto VOP nadobúdajú účinnosť dňom <!-- TODO: dátum účinnosti --> <strong>{{DD. MM. RRRR}}</strong>.
    </p>
  </section>

  <div class="sep"></div>
  <p style="opacity:.8;">Kontakt na Predávajúceho: <a href="mailto:boss@mistyhouse.sk">boss@mistyhouse.sk</a>, tel. <a href="tel:+421917595666">+421 917 595 666</a></p>
</main>

<?php get_footer(); ?>
