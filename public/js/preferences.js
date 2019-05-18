$(document).ready(function () {

    /*
    Start Insurance
     */
$('#checkAll').change(function () {
   $('.checkItem').prop("checked",$(this).prop("checked"));
    if ($('input[name="selected_id[]"]:checked').length >0){
        $('#deleteSelected').removeAttr('disabled');
    }  else{
        $('#deleteSelected').attr('disabled','disabled');

    }
});


$(function () {
    $('.checkItem').click(function () {
        if ($('input[name="selected_id[]"]:checked').length >0){
            $('#deleteSelected').removeAttr('disabled');
        }  else{
            $('#deleteSelected').attr('disabled','disabled');

        }
    });
});


$('#btn_bulk_delete_insurance').click(function () {
    $('#bulkDeleteInsuranceForm').submit();
});


    let table = $('#insurance_table').DataTable({
            "aLengthMenu": [
                [10, 30, 50, 100, -1],
                [10, 30, 50, 100, "All"]
            ],
            "iDisplayLength": 10,
            "language": {
                search: ""
            }
    });
    // table.column(4).visible(false);
    table.on('click','.edit',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = table.row($tr).data();

         $('#edit_name').val(data[3]);
        $('#edit_amount').val(data[4]);

        $('#editInsuranceForm').attr('action', 'insurance/'+data[2]);
        $('#editInsuranceModal').modal('show');
        $('#editInsuranceTitle').text(data[3]);
    });

    /*
    End Insurance
     */




    /*
    Start Charges
     */
    $('#checkAllCharges').change(function () {
        $('.checkItemCharge').prop("checked",$(this).prop("checked"));
        if ($('input[name="selected_charges[]"]:checked').length >0){
            $('#deleteSelectedCharge').removeAttr('disabled');
        }  else{
            $('#deleteSelectedCharge').attr('disabled','disabled');

        }
    });


    $(function () {
        $('.checkItemCharge').click(function () {
            if ($('input[name="selected_charges[]"]:checked').length >0){
                $('#deleteSelectedCharge').removeAttr('disabled');
            }  else{
                $('#deleteSelectedCharge').attr('disabled','disabled');

            }
        });
    });


    $('#btn_bulk_delete_charge').click(function () {
        $('#bulkDeleteChargeForm').submit();
    });


    let charge_table = $('#charge_table').DataTable({
        "aLengthMenu": [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"]
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        }
    });
    // table.column(4).visible(false);
    charge_table.on('click','.edit_charge',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = charge_table.row($tr).data();

        $('#edit_charge_name').val(data[3]);
        $('#edit_charge_amount').val(data[4]);

        $('#editChargeForm').attr('action', 'charges/'+data[2]);
        $('#editChargeModal').modal('show');
        $('#editChargeTitle').text(data[3]);
    });

    /*
    End Chages
     */
});