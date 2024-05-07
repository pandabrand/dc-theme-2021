document.addEventListener( 'DOMContentLoaded', animated );

function animated() {
  var menu = document.querySelectorAll('.letters');
  menu.forEach(function( textWrapper ) {
    textWrapper.innerHTML = textWrapper.textContent.replace(/\S/g, "<span class='letter'>$&</span>");
  })
  
  var animation = anime.timeline({loop: false});
  
  animation
  .add({
    targets: '.letters .letter',
    opacity: [0,0.76],
    easing: "linear",
    duration: 50,
    delay: anime.stagger(250),
  });
}

jQuery( document ).ready( function( $ ) {

  // Your JavaScript goes here

  const openMenu = document.getElementById("open-menu");
  const closeMenu = document.getElementById("close-menu");
  const menuItems = document.getElementById("menu-items");
  
  openMenu.addEventListener("click", (event) => {
    event.preventDefault();
    openMenu.classList.add("hidden");
    closeMenu.classList.remove("hidden");
    menuItems.classList.remove("hidden");
  });
  
  closeMenu.addEventListener("click", (event) => {
    event.preventDefault();
    openMenu.classList.remove("hidden");
    closeMenu.classList.add("hidden");
    menuItems.classList.add("hidden");
  });

  const lightbox = GLightbox({
    touchNavigation: true,
    loop: true,
    autoplayVideos: true,
  });
});
