$(document).ready(function () {

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
            let $val1 = parseInt($('.val1', this).text());
            let $val2 = parseInt($('.val2', this).val());
            let $val3 = parseInt($('.val3',this).val());
            let dispenseTotal = ($val1) * ($val3);
            let $total = ($val1) * ($val2);
            $('.multTotal',this).text($total);
            $('#drug_total_value',this).val($total);
            mult += $total;
            addDispense += dispenseTotal;

        });
         $("#drugsText").text(mult.toFixed(2));
        $("#drugs").val(mult);

        let  ser =Number($('#service').val());
        $("#grandText").text(Number(mult+ser).toFixed(2));
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
            let val1 = parseInt($('.val1', this).text());
            let val2 = parseInt($('.val2', this).val());
            let total = (val1) * (val2);
            $('.arrearsTotal',this).text(total);
            addition += total;
        });
        $("#drugsText").text(addition.toFixed(2));
        $("#drugs").val(addition);


        let  ser =Number($('#arrears').val());
        $("#grandText").text(Number(addition+ser).toFixed(2));
        $("#grand").val(addition+Number($('#arrears').val()));

    }
});
