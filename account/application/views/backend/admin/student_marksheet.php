<style>
    .exam_chart {
    width           : 100%;
        height      : 265px;
        font-size   : 11px;
}
  
</style>

<?php 
    $student_info = $this->crud_model->get_student_info($student_id);
    $exams         = $this->crud_model->get_exams();
    foreach ($student_info as $row1):
    foreach ($exams as $row2):
?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary panel-shadow" data-collapsed="0">
            <div class="panel-heading">
                <div class="panel-title"><?php echo $row2['name'];?></div>
            </div>
            <div class="panel-body">
                
                
               <div class="col-md-6">
                   <table class="table table-bordered">
                       <thead>
                        <tr>
                            <td style="text-align: center;">Subject</td>
                            <td style="text-align: center;">Obtained marks</td>
                            <td style="text-align: center;">Highest mark</td>
                            <td style="text-align: center;">Grade</td>
                            <td style="text-align: center;">Comment</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_marks = 0;
                            $total_grade_point = 0;
                            $subjects = $this->db->get_where('subject' , array('class_id' => $class_id))->result_array();
                            foreach ($subjects as $row3):
                        ?>
                            <tr>
                                <td style="text-align: center;"><?php echo $row3['name'];?></td>
                                <td style="text-align: center;">
                                    <?php
                                        $obtained_mark_query = $this->db->get_where('mark' , array(
                                                    'subject_id' => $row3['subject_id'],
                                                        'exam_id' => $row2['exam_id'],
                                                            'class_id' => $class_id,
                                                                'student_id' => $student_id));
                                        if ( $obtained_mark_query->num_rows() > 0) {
                                            $marks = $obtained_mark_query->result_array();
                                            foreach ($marks as $row4) {
                                                echo $row4['mark_obtained'];
                                                $total_marks += $row4['mark_obtained'];
                                            }
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php

                                    $highest_mark = $this->crud_model->get_highest_marks( $row2['exam_id'] , $class_id , $row3['subject_id'] );
                                    echo $highest_mark;


        
                                    ?>
                                </td>
                                <td style="text-align: center;">
                                    <?php
                                        if ($row4['mark_obtained'] >= 0 || $row4['mark_obtained'] != '') {
                                            $grade = $this->crud_model->get_grade($row4['mark_obtained']);
                                            echo $grade['name'];
                                            $total_grade_point += $grade['grade_point'];
                                        }
                                    ?>
                                </td>
                                <td style="text-align: center;"><?php echo $row4['comment'];?></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                   </table>

                   <hr />

                   <?php echo get_phrase('total_marks');?> : <?php echo $total_marks;?>
                   <br>
                   <?php echo get_phrase('average_grade_point');?> : 
                        <?php 
                            $this->db->where('class_id' , $class_id);
                            $this->db->from('subject');
                            $number_of_subjects = $this->db->count_all_results();
                            echo ($total_grade_point / $number_of_subjects);
                        ?>

                    <br> <br>
                    <a href="<?php echo base_url();?>index.php?admin/student_marksheet_print_view/<?php echo $student_id;?>/<?php echo $row2['exam_id'];?>" 
                        class="btn btn-primary" target="_blank">
                        <?php echo get_phrase('print_marksheet');?>
                    </a>
               </div>

               <div class="col-md-6">
                   <div id="chartdiv<?php echo $row2['exam_id'];?>" class="exam_chart"></div>
                       <script type="text/javascript">
                            var chart<?php echo $row2['exam_id'];?> = AmCharts.makeChart("chartdiv<?php echo $row2['exam_id'];?>", {
                                "theme": "none",
                                "type": "serial",
                                "dataProvider": [
                                        <?php 
                                            foreach ($subjects as $subject) :
                                        ?>
                                        {
                                            "subject": "<?php echo $subject['name'];?>",
                                            "mark_obtained": 
                                            <?php
                                                $obtained_mark = $this->crud_model->get_obtained_marks( $row2['exam_id'] , $class_id , $subject['subject_id'] , $row1['student_id']);
                                                echo $obtained_mark;
                                            ?>,
                                            "mark_highest": 
                                            <?php
                                                $highest_mark = $this->crud_model->get_highest_marks( $row2['exam_id'] , $class_id , $subject['subject_id'] );
                                                echo $highest_mark;
                                            ?>
                                        },
                                        <?php 
                                            endforeach;

                                        ?>
                                    
                                ],
                                "valueAxes": [{
                                    "stackType": "3d",
                                    "unit": "%",
                                    "position": "left",
                                    "title": "Obtained Mark vs Highest Mark"
                                }],
                                "startDuration": 1,
                                "graphs": [{
                                    "balloonText": "Obtained Mark in [[category]]: <b>[[value]]</b>",
                                    "fillAlphas": 0.9,
                                    "lineAlpha": 0.2,
                                    "title": "2004",
                                    "type": "column",
                                    "fillColors":"#7f8c8d",
                                    "valueField": "mark_obtained"
                                }, {
                                    "balloonText": "Highest Mark in [[category]]: <b>[[value]]</b>",
                                    "fillAlphas": 0.9,
                                    "lineAlpha": 0.2,
                                    "title": "2005",
                                    "type": "column",
                                    "fillColors":"#34495e",
                                    "valueField": "mark_highest"
                                }],
                                "plotAreaFillAlphas": 0.1,
                                "depth3D": 20,
                                "angle": 45,
                                "categoryField": "subject",
                                "categoryAxis": {
                                    "gridPosition": "start"
                                },
                                "exportConfig":{
                                    "menuTop":"20px",
                                    "menuRight":"20px",
                                    "menuItems": [{
                                        "format": 'png'   
                                    }]  
                                }
                            });
                    </script>
               </div>
               
            </div>
        </div>  
    </div>
</div>
<?php
    endforeach;
        endforeach;
?>