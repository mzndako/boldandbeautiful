<header class="main-header">

	<!-- Logo -->
	<a href="#" class="logo">
		<img src="<?php echo $this->c_->get_image_url('',-1,'logo');?>"  style="max-height:60px;"/>
	</a>

	<!-- Header Navbar -->
	<nav class="navbar navbar-static-top" role="navigation">
		<!-- Sidebar toggle button-->
		<a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
			<span class="sr-only">Toggle navigation</span>
		</a>
		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">




				<!-- User Account Menu -->
				<li class="dropdown user user-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<!-- The user image in the navbar-->
						<img src="<?php echo c()->get_image_url($login_as , $login_id);?>" class="user-image" alt="User Image">
						<!-- hidden-xs hides the username on small devices so only the image appears. -->
						<span class="hidden-xs"><i class="entypo-user"></i> <?php echo $this->session->userdata('name')." (".$this->session->userdata('login_as').")";?></span>
					</a>
					<ul class="dropdown-menu">
						<!-- The user image in the menu -->
						<li class="user-header">
							<img src="<?php echo c()->get_image_url($login_as , $login_id);?>" class="img-circle" alt="User Image">

							<p>
								<?php echo $this->session->userdata('name');?>
								<small><?php echo $this->session->userdata('login_as');?></small>
							</p>
						</li>
						<!-- Menu Body -->
<!--						<li class="user-body">-->
<!--							<div class="row">-->
<!--								<div class="col-xs-4 text-center">-->
<!--									<a href="https://almsaeedstudio.com/themes/AdminLTE/starter.html#">Followers</a>-->
<!--								</div>-->
<!--								<div class="col-xs-4 text-center">-->
<!--									<a href="https://almsaeedstudio.com/themes/AdminLTE/starter.html#">Sales</a>-->
<!--								</div>-->
<!--								<div class="col-xs-4 text-center">-->
<!--									<a href="https://almsaeedstudio.com/themes/AdminLTE/starter.html#">Friends</a>-->
<!--								</div>-->
<!--							</div>-->
<!--							<!-- /.row -->
<!--						</li>-->
						<!-- Menu Footer-->
						<li class="user-footer">
							<div class="pull-left">
								<a href="?admin/manage_profile" class="btn btn-default btn-flat">Profile</a>
							</div>
							<div class="pull-right">
								<a href="?login/logout" class="btn btn-default btn-flat">Sign out</a>
							</div>
						</li>
					</ul>
				</li>
				<!-- Control Sidebar Toggle Button -->
				<li>
<!--					<a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>-->
				</li>
			</ul>
		</div>
	</nav>
</header>

