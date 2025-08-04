// assets/js/tshirts.js
(function(){
  const container = document.getElementById('tshirts-container')
  const prevBtn   = document.querySelector('.tshirt-btn.prev')
  const nextBtn   = document.querySelector('.tshirt-btn.next')

  function step() {
    return container.clientWidth    // one “page” = width of 5 shirts
  }

  prevBtn.addEventListener('click', () => {
    container.scrollBy({
      left: -step(),
      behavior: 'smooth'
    })
  })

  nextBtn.addEventListener('click', () => {
    container.scrollBy({
      left: step(),
      behavior: 'smooth'
    })
  })
})()
