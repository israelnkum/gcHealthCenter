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



    let table = $('#patients_table').DataTable({
        "aLengthMenu": [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [
            // 'copy', 'csv', 'excel', 'pdf', 'print'
            'excel','pdf'
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        },

    });

    table.column(2).visible(false);


    $('#insurance').click(function () {
        if (!$(this).is(':checked')){
            //document.getElementById('insurance_number_div').style.display ='none';
            $('#insurance_number_div').fadeOut(1000).slideUp(1000);
            $('#insurance_number').removeAttr('required');
            $('#insurance_type').removeAttr('required');
        }  else{
            //alert("ch")
            // document.getElementById('insurance_number_div').style.display ='block';
            $('#insurance_number_div').fadeIn(1000).slideDown(1000);
            $('#insurance_number').attr('required', true);
            $('#insurance_type').attr('required',true);
        }
    });


    $('#old_patient').click(function () {
        if (!$(this).is(':checked')){
            //  document.getElementById('old_patient_div').style.display ='none';
            $('#old_patient_div').fadeOut(1000).slideUp(1000);
            $('#last_visit').removeAttr('required');
        }  else{
            //alert("ch")
            // document.getElementById('old_patient_div').style.display ='block';
            $('#old_patient_div').fadeIn(1000).slideDown(1000);
            $('#last_visit').attr('required', true);
        }
    });



    $('#register_patient').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            $('#patient_insured').prop('checked',false);
            $('#patient_insured').attr('disabled',true);
            $('#btn_register').attr('disabled',true);
            $('#patient_insurance_number_div').fadeOut(1000).slideUp(1000);
            $('#patient_insured_div').fadeOut(1000).slideUp(1000);
            $('#patient_charge_div').fadeOut(1000).slideUp(1000);
            //document.getElementById('patient_insurance_number_div').style.display ='none';
            //  document.getElementById('patient_insured_div').style.display ='none';
            //document.getElementById('patient_charge_div').style.display ='none';

        }  else{
            //alert("ch")
            $('#patient_insured').removeAttr('disabled');
            $('#btn_register').removeAttr('disabled');

            $('#patient_charges').attr('required',true);
            $('#patient_insured_div').fadeIn(1000).slideDown(1000);
            $('#patient_charge_div').fadeIn(1000).slideDown(1000);
            $('#patient_insurance_number').removeAttr('required');
            $('#patient_insurance_type').removeAttr('required');

            // document.getElementById('patient_charge_div').style.display ='block';
            //document.getElementById('patient_insured_div').style.display ='block';

        }
    });
    $('#patient_insured').click(function () {
        if (!$(this).is(':checked')){
            //alert("unckecked")
            //document.getElementById('patient_insurance_number_div').style.display ='none';
            $('#patient_insurance_number_div').fadeOut(1000).slideUp(1000);
            $('#patient_insurance_number').removeAttr('required');
            $('#patient_insurance_type').removeAttr('required');
        }  else{
            //alert("ch")
           // document.getElementById('patient_insurance_number_div').style.display ='block';

            $('#patient_insurance_number_div').fadeIn(1000).slideDown(1000);
            $('#patient_insurance_number').attr('required', true);
            $('#patient_insurance_type').attr('required',true);
        }
    });

});