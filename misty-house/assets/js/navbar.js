document.addEventListener('DOMContentLoaded', function() {
  const toggle = document.getElementById('mobile-menu-toggle');
  const links  = document.querySelector('.nav-links');

  if ( toggle && links ) {
    toggle.addEventListener('click', () => {
      links.classList.toggle('nav-links-mobile');
      toggle.classList.toggle('active');
    });

    // Close menu on link click
    links.querySelectorAll('a').forEach(a => {
      a.addEventListener('click', () => {
        links.classList.remove('nav-links-mobile');
        toggle.classList.remove('active');
      });
    });

    // Reset on resize
    window.addEventListener('resize', () => {
      if ( window.innerWidth > 768 ) {
        links.classList.remove('nav-links-mobile');
        toggle.classList.remove('active');
      }
    });
  }
});
