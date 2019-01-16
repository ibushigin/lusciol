// Transition de l'ancre 
$(function(){
	$('a[href^="#ScrollDown"]').click(function() {
          var target = $(this.hash);
          if (target.length == 0) target = $('a[name="ScrollDown' + this.hash.substr(1) + '"]');
          if (target.length == 0) target = $('html');
          $('html, body').animate({ scrollTop: target.offset().top }, 3000);
          return false;
      });

// Ecriture du slogan 
var string = "Nurture makes and distributes video content.";
var str = string.split("");
var el = document.getElementById('SloganLUSICOL');

function animate() {
str.length > 0 ? el.innerHTML += str.shift() : clearTimeout(running); 
var running = setTimeout(animate, 90);
};

setTimeout(animate, 1000);


})

