$(document).ready(function () {
    for (let i = 1; i <=5 ; i++) {
        $('#blood_pressure'+i).keyup(function () {
            if ($('#blood_pressure'+i).val()>37){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            } else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });

        $('#blood_pressure'+i).click(function () {
            if ($('#blood_pressure'+i).val()>37){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            } else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });
    }

});