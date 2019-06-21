/*
$(document).ready(function () {
/!*
    $('#checkAllDispensed').change(function () {
        $('.checkDispensedItem').prop("checked",$(this).prop("checked"));
    });

    $(function () {
        $('.checkDispensedItem').click(function () {
        });
    });

    let sum=0;
    for (let i = 1; i <=100 ; i++) {
        $('#numberDispensed'+i).keyup (function() {
            $("#total_price"+i).val($('#amount' + i).val() * $('#numberDispensed' + i).val());
            $('.price').each(function() {
                sum += Number($(this).val());
            });
            $(".grand_total").val(sum);
        });
    }*!/

    // $('.num').on('input', function() {
    //     console.log('New First name added');
    // });


     /!*   for (let i = 1; i <=100 ; i++) {
            $("#numberDispensed"+i).change(function(){
                $('.price').each(function() {
                    sum += Number($(this).val());
                });
                $(".grand_total").val(sum);

            });
        }
*!/
  /!*  $(document).on('keyup change','#sum_table tr:not(.totalCol) input:text', function() {
        var $table = $(this).closest('table');
        var total = 0;
        var thisNumber = $(this).attr('class').match(/(\d+)/)[1];

        $table.find('tr:not(.totalCol) .sum'+thisNumber).each(function() {
           let me =$('#sum_table').closest("tr").find("td:eq(1)").text();
            total += parseInt(this.value);
            alert(me);
        });



      //  $table.find('.totalCol td:nth-child('+thisNumber+')').html(total);
        $('.tot').val(total);
    });






//Creamos la funcion newSum para hacer la suma y mostrarlo en el total.
    $(document).on('keyup','input',newSum);
    function newSum() {
        var sum = 0;
        var thisRow = $(this).closest('tr');
        var total = 0;

        $(thisRow).find("td:not(.total) input").each(function () {

            sum += parseInt(this.value);
        });
        $(thisRow).find(".total").html(sum);
        $('.total').each(function () {
            total += parseInt($(this).html());
        });
    }*!/


    for (let i = 1; i <=100 ; i++) {
        $('#numberDispensed'+i).keyup (function() {
            $("#total_price"+i).text($('#numberDispensed'+i).val()*$('#amount'+i).val());
        });
    }

    let table = $('#sum_table').DataTable({
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
    table.on('keyup','.sum3',function () {
        $tr = $(this).closest('tr');

        if ($($tr).hasClass('child')){
            $tr = $tr.prev('.parent');
        }

        let data = table.row($tr).data();
        alert(Number(data[3].value))

    });

    /!*$(document).on('keyup change','#sum_table tr:not(.totalCol) input:text', function() {
        var $table = $(this).closest('table');
        var total = 0;
        var thisNumber = $(this).attr('class').match(/(\d+)/)[1];

        $table.find('tr:not(.totalCol) .sum'+thisNumber).each(function() {
            total += parseInt(this.value);
        });

        $table.find('.totalCol td:nth-child('+thisNumber+')').html(total);
    });*!/


});*/
$(document).ready(function () {

    $('#checkAllDispensed').change(function () {
        $('.checkDispensedItem').prop("checked",$(this).prop("checked"));
    });

    $(function () {
        $('.checkDispensedItem').click(function () {
        });
    });


    $(".txtMult input").keyup(multInputs);
    $(".txtMult input").click(multInputs);
    function multInputs() {
        var mult = 0;
        // for each row:
        $("tr.txtMult").each(function () {
            // get the values from this row:
            var $val1 = $('.val1', this).val();
            var $val2 = $('.val2', this).val();
            var $total = ($val1 * 1) * ($val2 * 1);
            $('.multTotal',this).text($total);
            mult += $total;
        });
         $("#drugsText").text(mult.toFixed(2));
        $("#drugs").val(mult);

        let  ser =Number($('#service').val());
        $("#grandText").text(Number(mult+ser).toFixed(2));
        $("#grand").val(mult+Number($('#service').val()));

    }
});
