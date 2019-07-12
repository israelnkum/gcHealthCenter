$(document).ready(function () {


    $("#select_drug_only").select2({
        dropdownParent: $('#add_drug .modal-content')
    });

    $("#select_dosage_only").select2({
        dropdownParent: $('#add_drug .modal-content')
    });

    /*
    Start Suppliers
     */
    $('#checkAllSuppliers').change(function () {
        $('.checkItemSupplier').prop("checked",$(this).prop("checked"));
        if ($('input[name="selected_suppliers[]"]:checked').length >0){
            $('#deleteSelectedSupplier').removeAttr('disabled');
        }  else{
            $('#deleteSelectedSupplier').attr('disabled','disabled');

        }
    });


    $("#select_drug_type").select2({
        dropdownParent: $('#upload_drugs .modal-content')
    });
    $("#unit_of_pricing").select2({
        dropdownParent: $('#newDrugModal .modal-content')
    });
    $("#drug_type1").select2({
        dropdownParent: $('#newDrugModal .modal-content')
    });

    $("#supplier_id").select2({
        dropdownParent: $('#upload_drugs .modal-content')
    });

    $("#unit_of_pricing").change(function () {
        if ($("#unit_of_pricing").val() == "Blister (x10tabs)"){
            $('#blisters').fadeIn(1000).slideDown(1000);
            $('#number_of_tablet').attr('required', true);

        }else {
            $('#blisters').fadeOut(1000).slideUp(1000);
            $('#number_of_tablet').removeAttr('required');
        }
    });



    $('#patient_insurance_type').attr('required',true);

    $(function () {
        $('.checkItemSupplier').click(function () {
            if ($('input[name="selected_suppliers[]"]:checked').length >0){
                $('#deleteSelectedSupplier').removeAttr('disabled');
            }  else{
                $('#deleteSelectedSupplier').attr('disabled','disabled');

            }
        });
    });


    $('#btn_bulk_delete_supplier').click(function () {
        $('#bulkDeleteSupplierForm').submit();
    });


    let table = $('#supplier_table').DataTable({
        "aLengthMenu": [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pageLength',
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1,3,4,5]
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1,3,4,5]
                }
            }
            // 'copy', 'csv', 'excel', 'pdf', 'print'
           // 'pageLength','excel','pdf'
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        },
    });
    table.column(2).visible(false);
    table.on('click','.edit',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = table.row($tr).data();

        $('#s_name').val(data[3]);
        $('#s_phone_number').val(data[4]);
        $('#s_email').val(data[5]);

        $('#editSupplierForm').attr('action', 'suppliers/'+data[2]);
        $('#editSupplierModal').modal('show');
        $('#editSupplierTitle').text(data[3]);
    });

    /*
    End Suppliers
     */



    /*
    Start Drug Types
     */
    $('#checkAllDrugTypes').change(function () {
        $('.checkItemDrugType').prop("checked",$(this).prop("checked"));
        if ($('input[name="selected_types[]"]:checked').length >0){
            $('#deleteSelectedType').removeAttr('disabled');
        }  else{
            $('#deleteSelectedType').attr('disabled','disabled');

        }
    });


    $(function () {
        $('.checkItemDrugType').click(function () {
            if ($('input[name="selected_types[]"]:checked').length >0){
                $('#deleteSelectedType').removeAttr('disabled');
            }  else{
                $('#deleteSelectedType').attr('disabled','disabled');

            }
        });
    });


    $('#btn_bulk_delete_drugType').click(function () {
        $('#bulkDeleteDrugTypeForm').submit();
    });


    let type_table = $('#drug_type_table').DataTable({
        "aLengthMenu": [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pageLength',
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1,3]
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1,3]
                }
            }
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        },
    });
    type_table.column(2).visible(false);
    type_table.on('click','.edit',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = type_table.row($tr).data();

        $('#edit_type_name').val(data[3]);


        $('#editDrugTypeForm').attr('action', 'drugs-types/'+data[2]);
        $('#editDrugTypeModal').modal('show');
        $('#editDrugTypeTitleTitle').text(data[3]);
    });

    /*
    End Drug Types
     */







    /*
    Start Drug
     */
    $('#checkAllDrugs').change(function () {
        $('.checkDrugItem').prop("checked",$(this).prop("checked"));
        if ($('input[name="selected_drugs[]"]:checked').length >0){
            $('#deleteSelectedDrugs').removeAttr('disabled');
        }  else{
            $('#deleteSelectedDrugs').attr('disabled','disabled');

        }
    });


    $(function () {
        $('.checkDrugItem').click(function () {
            if ($('input[name="selected_drugs[]"]:checked').length >0){
                $('#deleteSelectedDrugs').removeAttr('disabled');
            }  else{
                $('#deleteSelectedDrugs').attr('disabled','disabled');

            }
        });
    });


    $('#btn_bulk_delete_drug').click(function () {
        $('#bulkDeleteDrugForm').submit();
    });


    let drug_table = $('#drug_table').DataTable({
        "aLengthMenu": [
            [10, 30, 50, 100, -1],
            [10, 30, 50, 100, "All"]
        ],
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'pageLength',
            },
            {
                extend: 'excel',
                exportOptions: {
                    columns: [1,3,4,5,6,7,8]
                }
            },
            {
                extend: 'pdf',
                exportOptions: {
                    columns: [1,3,4,5,6,7,8]
                }
            }
        ],
        "iDisplayLength": 10,
        "language": {
            search: ""
        },
    });
    drug_table.column(2).visible(false);
    drug_table.column(9).visible(false);
    drug_table.column(10).visible(false);
    drug_table.on('click','.edit',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = drug_table.row($tr).data();

        $('#edit_drug_name').val(data[3]);
        $('#edit_supplier_id').val(data[9]).trigger('change');
        $('#edit_drug_type_id').val(data[10]).trigger('change');
        $('#quantity_stock').val(data[6]);
        $('#edit_cost_price').val(data[7]);
        $('#edit_retail_price').val(data[8]);

        $('#editDrugForm').attr('action', 'drugs/'+data[2]);
        $('#editDrugModal').modal('show');
        $('#editDrugTitle').text(data[3]);
    });

    /*
    End Drug
     */


});