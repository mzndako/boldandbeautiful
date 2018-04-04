<div class="row">
	<div class="col-md-8">
        <div class="row">
            <!-- CALENDAR-->
            <div class="col-md-12 col-xs-12">
                <div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_phrase('event_schedule');?>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:0px;">
                        <div class="calendar-env">
                            <div class="calendar-body">
                                <div id="notice_calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	<div class="col-md-4">
		<div class="row">

            <div class="col-md-12">
            <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="entypo-box"></i></div>
                    <div class="num" data-start="0" data-end="<?php

                   if(!is_admin()) {
                        d()->where("user_id",s()->userdata('user_id'));
                   }

                    d()->where("first_to <=",gdate());

                    $query = $this->db->get('appointments');
                    echo	$query->num_rows();?>" data-postfix="" data-duration="500" data-delay="0">0</div>

                    <h3><?php echo get_phrase('Appointments');?></h3>
                    <p>Total appointments</p>
                </div>

            </div>
            

            <?php if(is_admin()):?>
            <div class="col-md-12">
            
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php

                    $query = $this->c_->get_where('users',"is_admin",0) ;
                    echo	$query->num_rows();

                        ?>"
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('members');

                        ?></h3>
                   <p>Total Members</p>
                </div>
                
            </div>
            <div class="col-md-12">
            
                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-users"></i></div>
                    <div class="num" data-start="0" data-end="<?php

                    $query = $this->c_->get_where('users','is_admin',1) ;
                    echo	$query->num_rows();
                    ?>"
                    		data-postfix="" data-duration="800" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('admin');?></h3>
                   <p>Total Admin</p>
                </div>
                
            </div>
          <?php endif;?>
    	</div>
    </div>
	
</div>

<?php if(true):?>
    <script>
        $(document).ready(function() {

            var calendar = $('#notice_calendar');

            $('#notice_calendar').fullCalendar({
                header: {
                    left: 'title,month,basicWeek,basicDay,listWeek',
                    right: 'today prev,next'
                },
//                defaultDate: '2016-09-12',
                editable: true,
                eventLimit: true,
                navLinks: true,
//                weekNumbers: true,
//                weekNumbersWithinDays: true,
//                weekNumberCalculation: 'ISO',


                //defaultView: 'basicWeek',


                firstDay: 1,
                height: 530,
                droppable: false,

                events: [
                    {
                        title: 'Meeting of Abdul and Kasim',
                        start: '2016-09-12T10:30:00',
                        end: '2016-09-12T12:30:00',
                        color: "yellow",
                        textColor: "blue"
                    },

                    <?php

                    if(!is_admin()){
                        d()->where("user_id",login_id());
                    }
					$notices	=	$this->db->get('appointments')->result_array();
					foreach($notices as $row):
					    $time = strtotime($row['first_from']);
					    $start_date = date('"Y","m","d","H","i"',$time);

				        $time = strtotime($row['first_to']);
					    $end_date = date('"Y","m","d","H","i"',$time);
					?>
                    {
                        title: "<?php echo $spec[$row['specialization']];?>",
                        start: new Date(<?php echo $start_date;?>),
                        end: new Date(<?php echo $end_date;?>)

                    },
                    <?php
					endforeach
					?>

                ],
                eventClick: function(event) {
//                    $('#notice_calendar').fullCalendar('changeView', 'listWeek');
                }
            });
        });
    </script>

  <?php endif?>
