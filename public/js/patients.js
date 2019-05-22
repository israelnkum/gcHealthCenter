$(document).ready(function () {
    $("#gender").select2({
        dropdownParent: $('#newPatient .modal-content')
    });
    $("#marital_status").select2({
        dropdownParent: $('#newPatient .modal-content')
    });
    $("#religion").select2({
        dropdownParent: $('#newPatient .modal-content')
    });
    $("#insurance_type").select2({
        dropdownParent: $('#newPatient .modal-content')
    });

    $("#title").select2({
        dropdownParent: $('#newPatient .modal-content')
    });


    $('#register').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            $('#insured').prop('checked',false);
            $('#insured').attr('disabled',true);
            document.getElementById('insurance_number_div').style.display ='none';
            document.getElementById('insured_div').style.display ='none';
            document.getElementById('charge_div').style.display ='none';

        }  else{
            //alert("ch")
            $('#insured').removeAttr('disabled');

            $('#charges').attr('required',true);
            document.getElementById('charge_div').style.display ='block';
            document.getElementById('insured_div').style.display ='block';

        }
    });
    $('#insured').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            document.getElementById('insurance_number_div').style.display ='none';
            $('#insurance_number').removeAttr('required');
            $('#insurance_type').removeAttr('required');
        }  else{
            //alert("ch")
            document.getElementById('insurance_number_div').style.display ='block';
            $('#insurance_number').attr('required', true);
            $('#insurance_type').attr('required',true);
        }
    });




    $('#register_patient').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            $('#patient_insured').prop('checked',false);
            $('#patient_insured').attr('disabled',true);
            $('#btn_register').attr('disabled',true);
            document.getElementById('patient_insurance_number_div').style.display ='none';
            document.getElementById('patient_insured_div').style.display ='none';
            document.getElementById('patient_charge_div').style.display ='none';

        }  else{
            //alert("ch")
            $('#patient_insured').removeAttr('disabled');
            $('#btn_register').removeAttr('disabled');

            $('#patient_charges').attr('required',true);
            document.getElementById('patient_charge_div').style.display ='block';
            document.getElementById('patient_insured_div').style.display ='block';

        }
    });
    $('#patient_insured').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            document.getElementById('patient_insurance_number_div').style.display ='none';
            $('#patient_insurance_number').removeAttr('required');
            $('#patient_insurance_type').removeAttr('required');
        }  else{
            //alert("ch")
            document.getElementById('patient_insurance_number_div').style.display ='block';
            $('#patient_insurance_number').attr('required', true);
            $('#patient_insurance_type').attr('required',true);
        }
    });

});