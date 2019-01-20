// Fonction logo clignotant

$(function(){
    setInterval(function(){
        $('.DEMILOGO').fadeIn(1000);
        $('.DEMILOGO').fadeOut(1000);
    });
    $('.alert').fadeOut(6000);

    //Confirmations de formulaires

    // Pages Profile utilisateur -> suppression de compte
        $('body').on('click', '#deleteUserFromProfil', function() {
            return confirm('Votre compte sera definitivement supprimer, ainsi que toutes les informations vous concernant, êtes vous sur de vouloir continuer?');
        })

})


