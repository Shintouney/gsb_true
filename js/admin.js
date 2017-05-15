$(function(){
    var $trigger = $('#code_postal');
    var $target = $('#commune');
    var value = $trigger.val();

    generate_communes_selector(value, $target);

    $trigger.change(function(){
        generate_communes_selector($(this).val(), $target);
    });

    function generate_communes_selector(value, $target)
    {
        var url = 'index.php?page=user&action=displayCommuneByCodePostal&id=';
        var errormsg = 'Code postal non valide';
        generate_selector(url, value, $target, errormsg);
    }

    /* genere un select html a partir d'un autre champ du formulaire */
    function generate_selector(url, value, $target, errormsg)
    {
        if (!!value) {
            $target.siblings('span').hide();
            $target.css('visibility', 'visible');
            $.ajaxSetup({async: false});
            $target.load(url + value);
            if ($target.children('option').length == 0) {
                alert(errormsg);
                $target.css('visibility', 'hidden');
                $target.siblings('span').show();
            }
        } else {
            $target.siblings('span').show();
            $target.css('visibility', 'hidden');
        }
    }
    /* date picker calendrier */
    var defaultDate = $('#date_embauche').val();
    $('.datepicker').datepick({dateFormat: 'dd/mm/yyyy', alignment: 'top', defaultDate: defaultDate});


    /* confirm suppression */
    var msg = 'Voulez-vous supprimer cette utilisateur?';
    $('.delete').on('click',{msg : msg}, function(e){
        var answer=confirm(msg);
        if(!answer){
            e.preventDefault();
        }
    });
});

