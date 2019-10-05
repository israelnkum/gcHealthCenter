$(document).ready(function () {
    for (let i=1; i<=5; i++){
        $('#getLastVisitForm'+i).on('submit',function (e) {
            e.preventDefault();
            $.ajax({
                type: 'get',
                url: "registration",
                data: $("#getLastVisitForm"+i).serialize(),
                success: function (data) {
                    data = $.parseJSON(data);
                    //console.log(data[0].id);
                    /*$.each(data[count].consultation, function (index, value) {
                        html+=value.complains;
                    });*/
                    let html ='';
                    html +=' <div class="accordion accordion-bordered" id="accordion-'+(i+10)+'" role="tablist">';

                    for(let count =0; count<data.length; count++){
                        html +='<div class="card-header" role="tab" id="heading-4">';
                        html +='<a style="text-decoration:none" data-toggle="collapse" href="#GC'+data[count].id+'" aria-expanded="false" aria-controls="collapse-4">';
                        html +='<h5 class="mb-1 text-dark">';
                        html +='<i class="icon-calendar mr-1"></i>';

                        html += new Date(data[count].created_at).toUTCString().substr(0,16);
                        html +='</h5>';
                        html +='</a>';
                        html +='</div>';
                        html +='<div id="GC'+data[count].id+'" class="collapse" role="tabpanel" aria-labelledby="heading-4" data-parent="#accordion-'+(i+10)+'">';
                        html +='<div class="card-body">';
                        html +='<div class="row">';

                        $.each(data[count].consultation, function (index, value) {
                            $.each(data[count].vital, function (index, value) {
                                html +='<div class="row">';
                                html +='     <div class="col-md-8 mb-0">';
                                html +='<h6>Blood Pressure(BP) - <span class="text-danger">' + value.blood_pressure + ' mmHg</span></h6>';
                                html +='  </div>';
                                html +='  <div class="col-md-4 text-right">';
                                html +='      <h6>Weight - <span class="text-danger">' + value.weight + ' kg</span></h6>';
                                html +='  </div>';
                                html +='  <div class="col-md-6">';
                                html +='      <h6>Temperature - <span class="text-danger">' + value.temperature + ' °c</span></h6>';
                                html +='  </div>';

                                html +='  <div class="col-md-6 text-right">';
                                html +='      <h6>Pulse - <span class="text-danger">' + value.pulse + ' bpm</span></h6>';
                                html +='  </div>';
                                html +=' <div class="col-md-6">';
                                html +='<h6>Glucose - <span class="text-danger">' + value.glucose + ' mol</span></h6>';
                                html +='  </div>';

                                html +=' <div class="col-md-6 text-right">';
                                html +='     <h6>RDT - <span class="text-danger">' + value.RDT + '</span></h6>';
                                html +=' </div>';
                                html +=' </div>';
                            });
                            html +='<div class="col-md-6">';
                            html +='  <label class="text-info">Complains</label>';
                            html +='  <blockquote class="blockquote" >';
                            html +='  <small>'+value.complains+'</small>';

                            html +='  </blockquote>';
                            html +='  </div>';
                            html +=' <div class="col-md-6">';
                            html +='  <label class="text-info">Physical Examination</label>';
                            html +=' <blockquote class="blockquote">';
                            html +='    <small>'+value.physical_examination+'</small>';
                            html +=' </blockquote>';
                            html +='  </div>';

                            if (value.findings !== null) {
                                html += '<div class="col-md-6">';
                                html += '  <label class="text-info">History</label>';
                                html += '    <blockquote class="blockquote" >';
                                html += '   <small>' + value.findings + '</small>';
                                html += ' </blockquote>';
                                html += ' </div>';
                            }
                            if (value.notes !== null) {
                                html +='<div class="col-md-6">';
                                html +=' <label class="text-info">Notes</label>';
                                html +=' <blockquote class="blockquote" >';
                                html +=' <small>'+value.notes+'</small>';
                                html +=' </blockquote>';
                                html +=' </div>';
                            }
                            // @endif
                            // @if($visit->consultation[0]->diagnosis != "")
                            html +=' <div class="col-md-6">';
                            html +='   <label class="text-info">Diagnosis</label>';
                            html +='   <blockquote class="blockquote">';
                            $.each(data[count].diagnosis, function (index, value) {
                                html += ' <small>';

                                html += '<ul>';
                                html += ' <li><small>' + value['diagnoses'].name + '</small></li>';
                                html += '</ul>';
                                html +=' </small>';
                            });
                            if (value.other_diagnosis !== null) {
                                html +='  <small>'+value.other_diagnosis+'</small>';
                            }

                            html +='</blockquote>';
                            html +=' </div>';


                        });
                        // @endif

                        html += '<div class="col-md-6">';
                        html += '<label class="text-info">Medication</label>';
                        html += ' <blockquote class="blockquote">';
                        $.each(data[count].medications, function (index, value) {
                            html += ' <small>';

                            html += '<ul>';
                            html += ' <li><small>' + value['drugs'].name + '</small></li>';
                            html += '</ul>';
                            html +=' </small>';
                        });
                        /*html +=' </blockquote>';
                        html +=' </div>';

                        html +=' <div class="col-md-6">';
                        html +=' <label class="text-info">Diagnosis</label>';
                        html +=' <blockquote class="blockquote">';
                        html +='  <small>';*/
                        /*@php($medication[] = $visit->medication)
                        <ul>
                        @foreach($visit->diagnosis as $med)
                        <li><small>{{$med->diagnoses->name}}</small></li>
                    @endforeach*/
                        // @if($visit->consultation[0]->other_diagnosis != "")
                        // <li><small>{{$visit->consultation[0]->other_diagnosis}}</small></li>
                        //     @endif
                        //     </ul>
                        html +='</small>';
                        html +='</blockquote>';
                        html +='</div>';

                        html +='</div>';
                        html +='</div>';
                        html +='</div>';

                    }
                    html +='</div>';

                    $( ".last-visit-body"+i ).html(html);

                },
                error:function (error) {
                    console.log(error);
                }
            });
        });
    }
});