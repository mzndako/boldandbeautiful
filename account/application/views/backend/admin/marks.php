<div class="row">
	<div class="col-md-12">

		<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
				<a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('manage_marks'); ?>
				</a></li>
		</ul>
		<!------CONTROL TABS END------>


		<!----TABLE LISTING STARTS-->
		<div
			class="tab-pane  <?php if (!isset($edit_data) && !isset($personal_profile) && !isset($academic_result)) echo 'active'; ?>"
			id="list">
			<center>
				<?php echo form_open(base_url() . 'index.php?admin/marks'); ?>
				<table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
					<tr>
						<td><?php echo get_phrase('select_session'); ?></td>
						<td><?php echo get_phrase('select_term'); ?></td>
						<td><?php echo get_phrase('select_class'); ?></td>
						<td><?php echo get_phrase('select_exam'); ?></td>
						<td><?php echo get_phrase('select_subject'); ?></td>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>
							<select onchange="list_terms()" id="session" name="session_id" class="form-control"
							        data-validate="required"
							        data-message-required="<?php echo get_phrase('value_required'); ?>">
								<option value=""><?php echo get_phrase('select'); ?></option>
								<?php

								$terms = $this->c_->get('year')->result_array();


								foreach ($terms as $row2):
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
							<select onchange="list_exams()" id="term" name="term_id" class="form-control"
							        data-validate="required"
							        data-message-required="<?php echo get_phrase('value_required'); ?>">

							</select>
						</td>
						<td>
							<select onchange="list_exams()" data-validate="required" name="class_id" class="form-control " id="class"
							        style="float:left;">
								<option value=""><?php echo get_phrase('select_a_class'); ?></option>
								<?php
								$classes = $this->c_->get('class')->result_array();
								foreach ($classes as $row):
									?>
									<option value="<?php echo $row['class_id']; ?>"
										<?php if ($class_id == $row['class_id']) echo 'selected'; ?>>
										Class <?php echo $row['name']; ?></option>
									<?php
								endforeach;
								?>
							</select>
						</td>
						<td>
							<?php
							$ajaxExam = Array();
							$myexams = $this->c_->get('exam')->result_array();
							foreach ($myexams as $exam) {
								$ajaxExam[$exam['term_id']][$exam['class_id']][$exam['exam_id']] = $exam['name'];
							}

							?>
							<select name="exam_id"  data-validate="required" class="form-control " id="exam" style="float:left;">

							</select>



							<!--                            //start fo-->

							<!--                            //end formal-->
						</td>

						<td>
							<!-----SELECT SUBJECT ACCORDING TO SELECTED CLASS-------->
							<?php
							$ajaxSubject = Array();
							$mysubjs = $this->c_->get('subject')->result_array();
							foreach ($mysubjs as $subj) {
								$ajaxSubject[$subj['class_id']][$subj['subject_id']] = $subj['name'];
							}
							?>
							<select  data-validate="required" name="subject_id" class="form-control " id="subject" style="float:left;">

							</select>




						</td>
						<td>
							<input type="hidden" name="operation" value="selection"/>
							<input type="submit" value="<?php echo get_phrase('manage_marks'); ?>"
							       class="btn btn-info"/>
						</td>
					</tr>
				</table>
				</form>
			</center>


			<br/><br/>



<!--END OF SELECTING.............-->



			<?php if ($exam_id > 0 && $class_id > 0 && $subject_id > 0): ?>
				<?php
				////CREATE THE MARK ENTRY ONLY IF NOT EXISTS////
				$students = $this->crud_model->get_students($class_id);
				foreach ($students as $row):
					$verify_data = array('exam_id' => $exam_id,
						'class_id' => $class_id,
						'subject_id' => $subject_id,
						'student_id' => $row['student_id']);
					$query = $this->c_->get_where('mark', $verify_data);

					if ($query->num_rows() < 1)
						$this->c_->insert('mark', $verify_data);
				endforeach;

					$exam = $this->c_->get_where("exam",array("exam_id"=>$exam_id))->row();
				   $subject = $this->c_->get_subject_name_by_id($subject_id);

				?>
				<h3>
					<?php echo $exam->name." (".$exam->mark. " marks)
						 - $subject "; ?>
				</h3>

				<?php echo form_open(base_url() . 'index.php?admin/marks/' . $exam_id . '/' . $class_id); ?>
				<table class="table table-bordered">
					<thead>
					<tr>
						<td><?php echo get_phrase('admission_no'); ?></td>
						<td><?php echo count($students)." - ". get_phrase('students'); ?></td>
						<td><?php echo get_phrase('mark_obtained'); ?> (out of <?php echo $exam->mark; ?>)</td>
						<td><?php echo get_phrase('comment'); ?></td>
					</tr>
					</thead>
					<tbody>

					<?php
//					$students = $this->crud_model->get_students($class_id);

					foreach ($students as $row):

						$verify_data = array('exam_id' => $exam_id,
							'class_id' => $class_id,
							'subject_id' => $subject_id,
							'student_id' => $row['student_id']);

						$query = $this->c_->get_where('mark', $verify_data);
						$marks = $query->result_array();
						foreach ($marks as $row2):
							?>

							<tr>
								<td>
									<?php echo $row['admission_no']; ?>
								</td>
								<td>
									<?php echo $row['name']; ?>
								</td>
								<td>
									<input type="number" value="<?php echo $row2['mark_obtained']; ?>"
									       name="mark_obtained_<?php echo $row['student_id']; ?>" class="form-control">

								</td>
								<td>
									<textarea name="comment_<?php echo $row['student_id']; ?>"
									          class="form-control"><?php echo $row2['comment']; ?></textarea>
								</td>
								<input type="hidden" name="mark_id_<?php echo $row['student_id']; ?>"
								       value="<?php echo $row2['mark_id']; ?>"/>
							</tr>


							<?php
						endforeach;
					endforeach;

					?>



					<input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>"/>
					<input type="hidden" name="class_id" value="<?php echo $class_id; ?>"/>
					<input type="hidden" name="subject_id" value="<?php echo $subject_id; ?>"/>
					<input type="hidden" name="term_id" value="<?php echo $term_id; ?>"/>

					<input type="hidden" name="operation" value="update"/>
					</tbody>
				</table>

				<center>
					<button type="submit" class="btn btn-primary"><?php echo get_phrase('update_marks'); ?></button>
				</center>
				<?php echo form_close(); ?>

			<?php endif; ?>
		</div>
		<!----TABLE LISTING ENDS-->

	</div>
</div>
</div>

<script type="text/javascript">
	var exams = <?php echo json_encode($ajaxExam); ?>;

	var subjs = <?php echo json_encode($ajaxSubject); ?>;

	var currentexam = "<?php echo $exam_id; ?>";
	var currentsubject = "<?php echo $subject_id; ?>";

	<?php $this->c_->print_list_terms($term_id); ?>

	function list_exams() {
		var term_id = $("#term").val();
		var class_id = $("#class").val();

		try {
			$el = $("#exam");
			$el.html("");
			var lop = exams[term_id][class_id];
			$.each(lop, function (key, value) {
				if(currentexam == key){
					$el.append($("<option selected></option>")
						.attr("value", key).text(value));
				}else{
					$el.append($("<option></option>")
						.attr("value", key).text(value));
				}
			});
		} catch (e) {
		}

		try{
			$sub = $("#subject");
			$sub.html("");
			var lop = subjs[class_id];
			$.each(lop, function (key, value) {
				if(currentsubject == key){
					$sub.append($("<option selected></option>")
						.attr("value", key).text(value));
				}else{
					$sub.append($("<option></option>")
						.attr("value", key).text(value));
				}
			});
		}catch(e){}

	}

	list_exams();
	currentexam = "";
	currentsubject = "";

</script> 