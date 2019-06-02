$(document).ready(function () {
    for (let i = 1; i <=5 ; i++) {
        $('#systolic'+i).keyup(function () {
            if ($('#systolic'+i).val()>130 || $('#diastolic'+i).val()>90){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                $('.malaria'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            }else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('.malaria'+i).fadeOut(1000).slideDown(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });

        $('#diastolic'+i).keyup(function () {
            if ($('#systolic'+i).val()>130 || $('#diastolic'+i).val()>90){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                $('.malaria'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            }else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('.malaria'+i).fadeOut(1000).slideDown(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });
    }

    /*for (let i=1; i<=5; i++){
        $('#systolic'+i).click(function () {
            if ($('#systolic'+i).val()>130 || $('#diastolic'+i).val()>90){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                // $('.malaria'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            } else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('.malaria'+i).fadeOut(1000).slideDown(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });

        $('#diastolic'+i).click(function () {
            if ($('#systolic'+i).val()>130 || $('#diastolic'+i).val()>90){
                $('.sugar'+i).fadeIn(1000).slideDown(1000);
                $('.malaria'+i).fadeIn(1000).slideDown(1000);
                $('#rdt').attr('required',true);
                $('#glucose').attr('required',true);
            } else{
                $('.sugar'+i).fadeOut(1000).slideUp(1000);
                $('.malaria'+i).fadeOut(1000).slideDown(1000);
                $('#rdt').removeAttr('required');
                $('#glucose').removeAttr('required');
            }
        });
    }*/

});