<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
        	<?php if(isset($edit_profile)):?>
			<li class="active">
            	<a href="#edit" data-toggle="tab"><i class="icon-wrench"></i> 
					<?php echo get_phrase('edit_phrase');?>
                    	</a></li>
            <?php endif;?>
			<li class="<?php if(!isset($edit_profile))echo 'active';?>">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('language_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_phrase');?>
                    	</a></li>
			<li class="">
            	<a href="#add_lang" data-toggle="tab"><i class="entypo-plus-circled"></i> 
					<?php echo get_phrase('add_language');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
        
	
		<div class="tab-content">
            <!----PHRASE EDITING TAB STARTS-->
            <?php if (isset($edit_profile)):?>
			<div class="tab-pane active" id="edit" style="padding: 5px">
                <div class="">


						<div class="row">
                    	<?php 
						$current_editing_language	=	$edit_profile;
						echo form_open(base_url() . 'index.php?admin/manage_language/update_phrase/'.$current_editing_language  , array('id' => 'phrase_form'));
						$count = 1;
						$language_phrases	=	$this->db->query("SELECT `phrase_id` , `phrase` , `$current_editing_language` FROM `language`")->result_array();
						foreach($language_phrases as $row)
						{
							$count++;
							$phrase_id			=	$row['phrase_id'];					//id number of phrase
							$phrase				=	$row['phrase'];						//basic phrase text
							$phrase_language	=	$row[$current_editing_language];	//phrase of current editing language
							?>
                            <!----phrase box starts-->
                            <div class="col-sm-3">
                                <div class="tile-stats tile-gray">
                                    <div class="icon"><i class="entypo-mail"></i></div>
                                    
                                    
                                    <h3><?php echo $row['phrase'];?></h3>
                                    <p>
                                    	<input type="text" name="phrase<?php echo $row['phrase_id'];?>" 	
                                    		value="<?php echo $phrase_language;?>" class="form-control"/>
                                    </p>
                                </div>
                                
                            </div>
                            <!----phrase box ends-->
							<?php 
						}
						?>
						</div>
                        <input type="hidden" name="total_phrase" value="<?php echo $count;?>" />
                        <input type="submit" value="<?php echo get_phrase('update_phrase');?>" onClick="document.getElementById('phrase_form').submit();" class="btn btn-blue"/>	
                        <?php
						echo form_close();
						?>
                                     
                </div>                
			</div>
            <?php endif;?>
            <!----PHRASE EDITING TAB ENDS-->
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane <?php if(!isset($edit_profile))echo 'active';?>" id="list">
                
                
                <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
                	<thead>
                    	<tr>
                        	<th><?php echo get_phrase('language');?></th>
                        	<th><?php echo get_phrase('option');?></th>
                        </tr>
                    </thead>
                    <tbody>
                    	<?php
								$fields = $this->db->list_fields('language');
								foreach($fields as $field)
								{
									 if($field == 'phrase_id' || $field == 'phrase')continue;
									?>
                    	<tr>
                        	<td><?php echo ucwords($field);?></td>
                        	<td>
                            	<a href="<?php echo base_url();?>index.php?admin/manage_language/edit_phrase/<?php echo $field;?>"
                                	 class="btn btn-info">
                                		<?php echo get_phrase('edit_phrase');?>
                                </a>
                            	<a href="<?php echo base_url();?>index.php?admin/manage_language/delete_language/<?php echo $field;?>"
                                	rel="tooltip" data-placement="top" data-original-title="<?php echo get_phrase('delete_language');?>" class="btn btn-gray" onclick="return confirm('Delete Language ?');">
                                		<i class="icon-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----PHRASE CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open(base_url() . 'index.php?admin/manage_language/add_phrase/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('phrase');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="phrase" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_phrase');?></button>
                              </div>
							</div>
                    <?php echo form_close();?>                
                </div>                
			</div>
			<!----PHRASE CREATION FORM ENDS--->
            
        	<!----ADD NEW LANGUAGE---->
			<div class="tab-pane box" id="add_lang" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open(base_url() . 'index.php?admin/manage_language/add_language/' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('language');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="language" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_language');?></button>
                              </div>
							</div>
                    <?php echo form_close();?> 
                </div>
			</div>
            <!----LANGUAGE ADDING FORM ENDS-->
            
		</div>
	</div>
</div>