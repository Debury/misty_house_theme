(function () {
  function getHeaderHeight() {
    var maxH = 0;

    // Look for common header/nav containers and only count those that are fixed or sticky
    var selectors = ['#masthead', '.site-header', '.main-header', 'header', '.navbar', '.mh-header'];
    selectors.forEach(function (sel) {
      document.querySelectorAll(sel).forEach(function (el) {
        var cs = getComputedStyle(el);
        if (/(fixed|sticky)/.test(cs.position)) {
          maxH = Math.max(maxH, Math.ceil(el.getBoundingClientRect().height));
        }
      });
    });

    // Fallback if we didn’t find a sticky/fixed header
    if (!maxH) {
      var hdr = document.querySelector('header');
      if (hdr) maxH = Math.ceil(hdr.getBoundingClientRect().height);
    }
    return maxH || 120; // generous desktop fallback
  }

  function setOffsets() {
    var h = getHeaderHeight();
    var admin = document.getElementById('wpadminbar');
    var adminH = admin ? admin.offsetHeight : 0;
    document.documentElement.style.setProperty('--mh-header', h + 'px');
    document.documentElement.style.setProperty('--mh-admin', adminH + 'px');
  }

  window.addEventListener('load', setOffsets);
  window.addEventListener('resize', setOffsets);
})();
