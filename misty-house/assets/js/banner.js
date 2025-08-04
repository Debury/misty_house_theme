// assets/js/banner.js
(function(){
  let currentBannerIndex = 0;
  const container = document.getElementById('banner-container');
  const items = () => container.querySelectorAll('.banner-item');
  const prevBtn = document.querySelector('.banner-btn.prev');
  const nextBtn = document.querySelector('.banner-btn.next');
  const indicators = document.querySelectorAll('.banner-indicators .indicator');

  function updateBannerPosition() {
    if (!container || items().length === 0) return;
    const itemWidth = items()[0].getBoundingClientRect().width;
    const gap = parseFloat(getComputedStyle(items()[0]).marginRight);
    const shift = itemWidth + gap;
    container.style.transform = `translateX(-${currentBannerIndex * shift}px)`;
  }

  function updateIndicators() {
    indicators.forEach((ind, idx) => ind.classList.toggle('active', idx === currentBannerIndex));
  }

  function goToSlide(index) {
    currentBannerIndex = (index + items().length) % items().length;
    updateBannerPosition();
    updateIndicators();
  }

  if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentBannerIndex + 1));
  if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentBannerIndex - 1));
  indicators.forEach(ind => ind.addEventListener('click', e => {
    goToSlide(parseInt(e.currentTarget.dataset.slideTo, 10));
  }));

  window.addEventListener('resize', updateBannerPosition);
  document.addEventListener('DOMContentLoaded', () => {
    updateBannerPosition();
    updateIndicators();
  });
})();
