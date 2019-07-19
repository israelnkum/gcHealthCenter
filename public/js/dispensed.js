$(document).ready(function () {
    window.onload = function() {
        multInputs();
    };

    $(".txtMult input").keyup(multInputs);
    $(".txtMult input").click(multInputs);
    $(".txtMult input").hover(multInputs);

    // let table=  $("#dispenseTable").DataTable({
    //       // dom:'t'
    //   });
    //
    //   table.column(2).visible(false);
    function multInputs() {
        let mult = 0;
        let addDispense =0;
        // for each row:
        $("tr.txtMult").each(function () {
            // get the values from this row:
            let $val1 = parseFloat($('.val1', this).text());
            let $val2 = parseFloat($('.val2', this).text());
            let $val3 = parseFloat($('.val3',this).val());
            let dispenseTotal = ($val1) * ($val3);
            let $total = ($val1) * ($val2);
            $('.multTotal',this).text(Number($total).toFixed(2));
            $('#drug_total_value',this).val($total);
            mult += $total;
            addDispense += dispenseTotal;

        });
        $("#drugsText").text(mult.toFixed(2));
        $("#drugs").val(mult);

        let  ser =Number($('#service').val());
        $("#grandText").text(Number(mult+ser).toFixed(2));

        /*     let me = parseFloat($("#detention_input").val())+mult+ser;
             $("#bill_grandText").text(me);*/

        $("#grand").val(mult+Number($('#service').val()));

        $('.dispenseTotal').text(Number(addDispense+ser).toFixed(2));
    }





    $(".arrearsMulti input").keyup(multiplyInputs);
    $(".arrearsMulti input").click(multiplyInputs);
    $(".arrearsMulti input").hover(multiplyInputs);
    function multiplyInputs() {
        let addition = 0;
        // for each row:
        $("tr.arrearsMulti").each(function () {
            // get the values from this row:
            let val1 = parseFloat($('.val1', this).text());
            let val2 = parseFloat($('.val2', this).val());
            let total = (val1) * (val2);

            $('.arrearsTotal',this).text(Number(total).toFixed(2));
            addition += total;
        });
        $("#drugsText").text(addition.toFixed(2));
        $("#drugs").val(addition);


        let  ser =Number($('#arrears').val());
        $("#grandText").text(Number(addition+ser).toFixed(2));
        $("#grand").val(addition+Number($('#arrears').val()));

    }

    $('#amount_paid_input').keyup(calculateArrears);
    function calculateArrears() {
        let amount_paid = parseFloat($('#amount_paid_input').val());
        let grand_total = parseFloat($('#detention_input').val());
        $("#bill_amt_txt").text($('#amount_paid_input').val());

        $("#bill_arrears_text").text(Number((grand_total) - (amount_paid)).toFixed(2));
    }



    $('.dosage').change(function () {
        for(let i=0; i<10; i++) {
            let days = $('.days')[i].val();
            let qty = $('.qty')[i].val();
            $('.qty')[i].val(5);
        }


    });
});
