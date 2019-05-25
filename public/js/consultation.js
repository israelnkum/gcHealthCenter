$(document).ready(function () {

    $('#other_diagnosis_check').click(function () {
        if (!$(this).is(':checked')){
            document.getElementById('other_diagnosis_div').style.display ='none';
            $('#other_diagnosis_text').removeAttr('required');
            $('#select_diagnosis').attr('required',true);
            $('#select_diagnosis').attr('disabled',false);
        }  else{
            $('#select_diagnosis').removeAttr('required');
            $('#select_diagnosis').attr('disabled',true);
            $('#other_diagnosis_text').attr('required',true);
            document.getElementById('other_diagnosis_div').style.display ='block';

        }
    });
});