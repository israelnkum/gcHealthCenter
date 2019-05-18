$(document).ready(function () {

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


$('#btn_submit_bulk_delete').click(function () {
    $('#bulkDeleteForm').submit();
});


    let table = $('#users_table').DataTable({
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

//     console.log(data);


         $('#edit_username').val(data[4]);
        $('#edit_email').val(data[5]);
        $('#edit_phone_number').val(data[6]);



         //let myArray = data[4].substring(0, data[4].length - 1).split(',');
         $('#edit_role').val(data[7]).trigger('change');

        $('#editStaffForm').attr('action', 'users/'+data[3]);
        $('#editStaff').modal('show');
        $('#editStaffTitle').text(data[4]);
    });


    //delete gift card
    table.on('click','.delete',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = table.row($tr).data();

        $('#deleteLevelForm').attr('action', '/levels/'+data[1]);
        $('#deleteLevelModal').modal('show');
        $('#deleteCategoryTitle').text("Delete "+data[2]);
        $('#deleteCategoryItem').text(data[2]);
    })
});