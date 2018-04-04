
<br><br>
<style>
	.mytable td{
		padding: 10px;
	}
</style>
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/members_add/<?=$type;?>');"
   class="btn btn-primary pull-right">
	<i class="entypo-plus-circled"></i>
	<?php echo get_phrase('add new member');?>
</a>
<br><br>
<style>
	.mytable td{
		padding: 10px;
	}
</style>
<div >
	<br>
	<h3>Showing <?=count($members)." $type_";?> </h3>

					<table class="table table-bordered datatable table-striped" id="table_export">
						<thead>
						<tr>
							<th>#</th>
							<th><?php echo get_phrase('image');?></th>
							<th><?php echo get_phrase('name');?></th>
							<?php if($type == "admin"):?>
								<th><?php echo get_phrase('specializations');?></th>
							<?php endif;?>
							<th><?php echo get_phrase('residential_address');?></th>
							<th><?php echo get_phrase('email');?></th>
							<th><?php echo get_phrase('phone');?></th>
							<th><?php echo get_phrase('options');?></th>
						</tr>
						</thead>
						<tbody>
						<?php
						$count    = 1;
$total = 0;
						foreach ($members as $row):
							?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><img src="<?php echo $this->crud_model->get_image_url('users',$row['id']);?>" class="img-circle" width="30" /></td>
								<td><?php echo c()->get_full_name($row);?></td>

								<?php if($type == "admin"):?>
								<td><?php echo @$specs[$row['specialization']]['name'];?></td>
								<?php endif;?>

								<td><?php echo $row['residential_address'];?></td>

								<td><?php echo $row['email'];?>	</td>
								<td><?php echo $row['phone'];?>	</td>


								<td>

									<div class="btn-group">
										<button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
											Action <span class="caret"></span>
										</button>
										<ul class="dropdown-menu dropdown-default pull-right" role="menu">

											<?php if($login_id == $row['id']):?>
												<li>
													<a href="<?php echo base_url();?>?admin/manage_profile">
														<i class="entypo-book-open"></i>
														<?php echo get_phrase('update_my_account');?>
													</a>
												</li>
												<li class="divider"></li>
											<?php endif; ?>


											<li>
												<a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/members_add/<?php echo $type.'/'.$row['id'];?>/show');">
													<i class="entypo-user"></i>
													<?php echo get_phrase('profile');?>
												</a>
											</li>

											<?php if($s_->hAccess('manage_members') || $s_->hAccess('manage_admin')): ?>
												<li>
													<a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/members_add/<?php echo $type.'/'.$row['id'];?>');">
														<i class="entypo-pencil"></i>
														<?php echo get_phrase('edit');?>
													</a>
												</li>
											<?php endif; ?>

											<?php if($s_->hAccess('manage_members') || $s_->hAccess('manage_admin')): ?>
												<li class="divider"></li>

												<li>
													<a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/view_members/<?php echo $type;?>/delete/<?php echo $row['id'];?>');">
														<i class="entypo-trash"></i>
														<?php echo get_phrase('delete');?>
													</a>
												</li>
											<?php endif; ?>

										</ul>
									</div>

								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>

