<?php
	$class_name		 	= 	$this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
	$exam_name  		= 	$this->db->get_where('exam' , array('exam_id' => $exam_id))->row()->name;
	$system_name        =	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
?>


<div id="print">

	<script src="assets/js/jquery-1.11.0.min.js"></script>
	<style type="text/css">
		td {
			padding: 5px;
		}
	</style>

	<center>
		<img src="uploads/logo.png" style="max-height : 60px;"><br>
		<h3 style="font-weight: 100;"><?php echo $system_name;?></h3>
		<?php echo get_phrase('student_marksheet');?><br>
		<?php echo $this->db->get_where('student' , array('student_id' => $student_id))->row()->name;?><br>
		<?php echo get_phrase('class') . ' ' . $class_name;?><br>
		<?php echo $exam_name;?>
	</center>

	<table style="width:100%; border-collapse:collapse;border: 1px solid #ccc; margin-top: 10px;" border="1">
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
                        $marks = $this->db->get_where('mark' , array(
                                    'subject_id' => $row3['subject_id'],
                                        'exam_id' => $exam_id,
                                            'class_id' => $class_id,
                                                'student_id' => $student_id))->result_array();
                        
                        foreach ($marks as $row4) {
                            echo $row4['mark_obtained'];
                            $total_marks += $row4['mark_obtained'];
                        }
                    ?>
                </td>
                <td style="text-align: center;">
                    <?php

                    $highest_mark = $this->crud_model->get_highest_marks( $exam_id , $class_id , $row3['subject_id'] );
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

<br>

    <center>
	   <?php echo get_phrase('total_marks');?> : <?php echo $total_marks;?>
	   <br>
	   <?php echo get_phrase('average_grade_point');?> : 
	        <?php 
	            $this->db->where('class_id' , $class_id);
	            $this->db->from('subject');
	            $number_of_subjects = $this->db->count_all_results();
	            echo ($total_grade_point / $number_of_subjects);
	        ?>
	</center>

</div>


<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		var elem = $('#print');
		PrintElem(elem);
		Popup(data);

	});

    function PrintElem(elem)
    {
        Popup($(elem).html());
    }

    function Popup(data) 
    {
        var mywindow = window.open('', 'my div', 'height=400,width=600');
        mywindow.document.write('<html><head><title></title>');
        //mywindow.document.write('<link rel="stylesheet" href="assets/css/print.css" type="text/css" />');
        mywindow.document.write('</head><body >');
        //mywindow.document.write('<style>.print{border : 1px;}</style>');
        mywindow.document.write(data);
        mywindow.document.write('</body></html>');

        mywindow.document.close(); // necessary for IE >= 10
        mywindow.focus(); // necessary for IE >= 10

        mywindow.print();
        mywindow.close();

        return true;
    }
</script>