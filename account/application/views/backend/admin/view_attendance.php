<?php 
    $active_sms_service = $this->c_->get_where('settings' , array('type' => 'active_sms_service'))->row()->description;
?>
<hr />
<div class="row">


    <form method="post" action="<?php echo base_url();?>index.php?admin/view_attendance/<?=$mtype;?>" class="form">

    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('select_session');?></th>
            	<th><?php echo get_phrase('select_term');?></th>
                <?php if($is_student): ?>
            	    <th><?php echo get_phrase('select_class');?></th>
                <?php else: ?>
                    <th><?php echo get_phrase('select_staff_categories');?></th>
                <?php endif; ?>
                <th><?php echo get_phrase('type');?></th>
                <th><?php echo get_phrase('view');?></th>
           </tr>
       </thead>
		<tbody>


            	<tr class="gradeA">
                    <td>
                        <select onchange="list_terms()" id="session" name="session_id" class="form-control"
                                data-validate="required"
                                data-message-required="<?php echo get_phrase('value_required'); ?>">
                            <option value=""><?php echo get_phrase('select'); ?></option>
                            <?php

                            $sessions = $this->c_->get('year')->result_array();
                            foreach ($sessions as $row2):
                                ?>
                                <option <?php echo $session_id == $row2['year_id'] ? "selected" : ""; ?>
                                    value="<?php echo $row2['year_id']; ?>">
                                    <?php echo $row2['name'];; ?>
                                </option>
                                <?php
                            endforeach;
                            ?>
                        </select>
                    </td>
                    <td>
                        <select id="term" name="term_id" class="form-control"
                                data-validate="required"
                                data-message-required="<?php echo get_phrase('value_required'); ?>">

                        </select>
                    </td>

                    <td>
                        <?php if($is_student): ?>
                            <select name="class_id" class="form-control" required>
                                <option value="">Select a class</option>
                                <?php
                                $data = array();
                                if($this->c_->isStudent()){
                                    $ids = $this->c_->get_ids("student",array("student_id"=>$login_id),"class_id");
                                    if(!empty($ids))
                                        $this->db->where_in("class_id",$ids);
                                    else d()->where("class_id",0);

                                }elseif($this->c_->isTeacher(true)){
                                    $ids = $this->c_->get_ids("class",array("teacher_id"=>$login_id),"class_id");
                                    if(!empty($ids))
                                        $this->db->where_in("class_id",$ids);
                                    else d()->where("class_id",0);

                                }elseif($this->c_->isParent()){
                                    $ids = $this->c_->get_all_student_for_parent($login_id);
                                    foreach($ids as $id){
                                        d()->or_where("student_id",$id);
                                    }
                                    $ids = $this->c_->get_ids("class",array(),"class_id");
                                    if(!empty($ids))
                                        $this->db->where_in("class_id",$ids);
                                    else d()->where("class_id",0);
                                }


                                $classes	=	$this->c_->get('class')->result_array();
                                foreach($classes as $row):?>
                                    <option value="<?php echo $row['class_id'];?>"
                                        <?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
                                        <?php echo $row['name'];?>
                                    </option>
                                <?php endforeach;?>
                                <?php if($this->c_->isAdmin()):?><option value="0" <?php if(isset($class_id) && $class_id===0)echo 'selected="selected"';?>>All Classes</option>
                                <?php endif;;?>
                            </select>




                    <?php else: ?>
                            <select name="class_id" class="form-control" required>
                                <option value="">Select Staff Category</option>
                                <?php
                                $classes	=	$this->c_->get('teacher_categories')->result_array();
                                foreach($classes as $row):?>
                                    <option value="<?php echo $row['category_id'];?>"
                                        <?php if(isset($class_id) && $class_id==$row['category_id'])echo 'selected="selected"';?>>
                                        <?php echo $row['name'];?>
                                    </option>
                                <?php endforeach;?>
                                <option value="0" <?php if(isset($class_id) && $class_id===0)echo 'selected="selected"';?>>All Staffs</option>
                            </select>

                        <?php endif; ?>


                    </td>

                    <td>
                    	<select name="type" id="type" class="form-control" onchange="showDate(this)"  required>

                        	<?php
							$op	=	c()->get_option_type();

							foreach($op as $k => $v):?>
                        	<option value="<?php echo $k;?>"
                            	<?php if(isset($type) && $type==$k)echo 'selected="selected"';?>>
									<?php echo $v;?>
                              			</option>
                            <?php endforeach;?>
                        </select>

                    </td>


                    <td align="center"><input type="submit" value="<?php echo get_phrase('view_attendance');?>" class="btn btn-info"/></td>
                </tr>

		</tbody>
	</table>
        <div id="showDate" style="display: none;" align="left">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                <thead>
                <tr>

                    <th><?php echo get_phrase('from');?></th>
                    <th><?php echo get_phrase('to');?></th>
                </tr>
                </thead>
                <tbody>


                <tr class="gradeA">
                    <td>
                        <input id="input-date1" type="hidden" data-field="date" name="startdate"
                               value="<?=$startdate;?>">

                        <div id="dtBox1" ></div>
                    </td>
                    <td>
                        <input id="input-date2" type="hidden" data-field="date" name="enddate"
                               value="<?=$enddate;?>">

                        <div id="dtBox2" ></div>

                    </td>

                </tr>

                </tbody>
            </table>
        </div>


    </form>
</div>

<hr />



<?php if($class_id != ""): ?>
<div class="row">
    <div id="chartdiv" style="width:100%; height:400px;"></div>
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
        <thead>
        <tr>
            <th><?php echo $is_student?get_phrase('adm no'):"Staff ID";?></th>
            <th><?php echo get_phrase('image');?></th>
            <th><?php echo get_phrase('name');?></th>
            <?php
                if(count($atd['dates']) < 15):
                    foreach($atd['dates'] as $value):
            ?>
                <th><?php echo date("D (j M, y)",strtotime($value))?></th>
            <?php
                    endforeach;
                ?>
                <th>Total Present</th>
            <?php
                else:
            ?>
            <th>Total Present</th>
            <?php endif;?>
            <th><?php echo get_phrase('view');?></th>
        </tr>
        </thead>
        <tbody>

        <?php
        if($is_student){
            $table = "student";
            $myid = 'student_id';
            $table_id = 'class_id';
            $name = "student";
        }else{
            $table = "teacher";
            $myid = 'teacher_id';
            $table_id = 'tc';
            $name = "teacher";
        }

        if($class_id == 0){
            $students = c()->get($table)->result_array();
        }else
            $students = c()->get_where($table,$table_id,$class_id)->result_array();

        foreach($students as $row):
            $a = isset($atd['total'][$row[$myid]])?$atd['total'][$row[$myid]]:0;

            $img = $this->crud_model->get_image_url($name,$row[$myid]);

                $charts[]=array("student"=>c()->get_short_name($row),"g1"=>$a,"g2"=>$a,"img"=>$img,"absent"=>$atd['max']
                - $a,"adm_no"=>$is_student?$row['admission_no']:$row[$myid]);

        ?>
        <tr>
            <td><?php echo $is_student?$row['admission_no']:$row['teacher_id'];?></td>
            <td><img src="<?php echo $this->crud_model->get_image_url($name,$row[$myid]);?>" class="img-circle" width="30" /></td>
            <td><?php echo $row['surname'],', ',$row['fname'],' ',$row['mname'];?></td>
            <?php
            if(count($atd['dates']) < 15):
                foreach($atd['dates'] as $value):
                    ?>
                    <td><?php echo get_status(@$atd['attendance'][$value][$row[$myid]]);?></td>
                    <?php
                endforeach;
                ?>
                <?php
            endif;
                ?>
                <td><?php echo getIndex($atd,"total,$row[$myid]",0); ?></td>
<!--                <td>--><?php //echo @$atd['total'][$row[$myid]]; ?><!--</td>-->

            <td>

            </td>

        </tr>
<?php endforeach;?>
        </tbody>
    </table>
</div>
    <script type="text/javascript">
        var chart;
        var chartData = <?php print json_encode($charts);?>;


                AmCharts.ready(function () {
            // SERIAL CHART
            //alert();
            chart = new AmCharts.AmSerialChart();

            chart.dataProvider = chartData;
            chart.categoryField = "student";
            chart.startDuration = 1;

            chart.handDrawn = false;
            chart.handDrawnScatter = 3;

            // AXES
            // category
            var categoryAxis = chart.categoryAxis;
            categoryAxis.gridPosition = "start";
            categoryAxis.labelRotation = 45;


            // value
            var valueAxis = new AmCharts.ValueAxis();
            valueAxis.axisAlpha = 0;
            chart.addValueAxis(valueAxis);

            // GRAPHS
            // column graph
            var graph1 = new AmCharts.AmGraph();
            graph1.type = "column";
            graph1.title = "Student";
            graph1.lineColor = "#a668d5";
            graph1.valueField = "g1";
            graph1.lineAlpha = 1;
            graph1.fillAlphas = 1;
            graph1.dashLengthField = "dashLengthColumn";
            graph1.alphaField = "alpha";
            graph1.balloonText = "<span style='font-size:13px;text-align: left;" +
                "'><img  src='[[img]]' class=img-circle " +
                "width=30><br> [[adm_no]]<br>[[category]]<br><b style='color: green;'>PRESENT: [[value]]</b>" +
                " " +
                "<BR><b style='color: red;'>ABSENT: [[absent]] </b></span>";
            chart.addGraph(graph1);

            // line
            var graph2 = new AmCharts.AmGraph();
            graph2.type = "line";
            graph2.title = "Student";
            graph2.lineColor = "#fcd202";
            graph2.valueField = "g2";
            graph2.lineThickness = 3;
            graph2.bullet = "round";
            graph2.bulletBorderThickness = 3;
            graph2.bulletBorderColor = "#fcd202";
            graph2.bulletBorderAlpha = 1;
            graph2.bulletColor = "#ffffff";
            graph2.dashLengthField = "dashLengthLine";
            graph2.balloonText = "<span style='font-size:13px;text-align: left;" +
                "'><img  src='[[img]]' class=img-circle " +
                "width=30><br> [[adm_no]]<br>[[category]]<br><b style='color: green;'>PRESENT: [[value]]</b>" +
                " " +
                "<BR><b style='color: red;'>ABSENT: [[absent]] </b></span>";
            chart.addGraph(graph2);

            // LEGEND
            var legend = new AmCharts.AmLegend();
            legend.useGraphSettings = true;
            chart.addLegend(legend);

            // WRITE
            // alert();
            chart.write("chartdiv");
        });
    </script>
<?php endif;?>




<script type="text/javascript">

    function showDate(me){
        if(me != undefined)
            var num = me.selectedIndex;
        else
            var num = document.getElementById("type").selectedIndex;
        if(num == <?php echo c()->get_option_type("specific date");?>){
            $("#showDate").show(500);
        }else{
            $("#showDate").hide(500);
        }
    }


    $(document).ready(function()
    {


        $("#dtBox1").DateTimePicker({

            isInline: true,

            inputElement: $("#input-date1"),

            buttonsToDisplay: [],

            dateFormat:	"dd-MM-yyyy",

            showHeader: false,

            readonlyInputs: false,

            setValueInTextboxOnEveryClick: true,

            settingValueOfElement: function(oDTP, sElemValue, dElemValue, $oElem)
            {

            }

        });

    });

    $(document).ready(function()
    {


        $("#dtBox2").DateTimePicker({

            isInline: true,

            inputElement: $("#input-date2"),

            buttonsToDisplay: [],

            dateFormat:	"dd-MM-yyyy",

            showHeader: false,

            readonlyInputs: false,

            setValueInTextboxOnEveryClick: true,

            settingValueOfElement: function(oDTP, sElemValue, dElemValue, $oElem)
            {

            }

        });

    });

    <?php  $this->c_->print_list_terms($term_id); ?>
    showDate();
</script>