// Transition de l'ancre 
$(function(){
	$('a[href^="#ScrollDown"]').click(function() {
          var target = $(this.hash);
          if (target.length == 0) target = $('a[name="ScrollDown' + this.hash.substr(1) + '"]');
          if (target.length == 0) target = $('html');
          $('html, body').animate({ scrollTop: target.offset().top }, 1000);
          return false;
      });

// Ecriture du slogan
var BUTTONMap = document.getElementById('BUTTONMap');
var string = "Tous les bons plans étudiant à portée de clic!";
var str = string.split("");
var el = document.getElementById('SloganLUSCIOL');

function animate() {
str.length > 0 ? el.innerHTML += str.shift() : clearTimeout(running);
var running = setTimeout(animate, 90);
// Cacher - Apparaitre ma DIV bouton
//     if(el.innerText.length > 43){
//         BUTTONMap.style.display = "block";
//     }
};
// Lancement de l'annimation
setTimeout(animate, 1000);


//Delay sur le bouton "trouver un bon plan"

    setTimeout(function(){
        $('#BUTTONMap').fadeIn( "slow" );
    }, 4960)

// Faire briller un point
setTimeout(function(){
    setInterval(function(){
        $('#mainbgimg2').fadeIn(1000);
        $('#mainbgimg2').fadeOut(1000);
    }, 2000);
}, 1960)


})

