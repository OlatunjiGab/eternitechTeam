		<!-- Navbar Right Menu -->
		<div class="navbar-custom-menu">
			<ul class="nav navbar-nav">
				<?php 
				$impersinateout = session('impersonate');
  				 if(isset($impersinateout))
  				 {
  				 	echo '<li><a href="/impersonate/destroy" class="btn btn-success  btn-xs" style="margin-right: 10px;color: white;">Stop Impersonating</a></li>';
  				 } 
 				?>
				<!-- Messages: style can be found in dropdown.less-->
				@if(LAConfigs::getByKey('show_messages'))
				@if(Entrust::hasRole("SUPER_ADMIN"))
				<li class="dropdown messages-menu">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-envelope-o"></i>
						<span class="label label-success">{{count($unReadMessages)}}</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">You have {{count($unReadMessages)}} messages</li>
						<li>
							<ul class="menu">
								@foreach($unReadMessages as $message)
								<li>
									<a href="{{ url(config('laraadmin.adminRoute') . '/projects/is-read/'.$message->id) }}">
										<div class="pull-left">
											<span><i style="font-size: 2rem;" class="fa fa-envelope"></i></span>
										</div>
										<h4><?php echo $message->project_name; ?></h4>

										<p><?php echo strip_tags(substr($message->event_message, 0, 100).'...'); ?></p>
									</a>
								</li>
								@endforeach
							</ul>
						</li>
{{--						<li class="footer"><a href="#">See All Messages</a></li>--}}
					</ul>
				</li><!-- /.messages-menu -->
				@endif
				@endif
				@if(LAConfigs::getByKey('show_notifications'))
				<!-- Notifications Menu -->
				<li class="dropdown notifications-menu">
					<!-- Menu toggle button -->
					{{--<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-bell-o"></i>
						<span class="label label-warning">0</span>
					</a>--}}
					<ul class="dropdown-menu">
						<li class="header">Not found.</li>
						<?php /*
						<li class="header">You have 10 notifications</li>
						<li>
							<!-- Inner Menu: contains the notifications -->
							<ul class="menu">
								<li><!-- start notification -->
									<a href="#">
										<i class="fa fa-users text-aqua"></i> 5 new members joined today
									</a>
								</li><!-- end notification -->
							</ul>
						</li>
						 */ ?>
						<li class="footer"><a href="javascript:void(0);">View all</a></li>
					</ul>
				</li>
				@endif
				<?php /* @if(LAConfigs::getByKey('show_tasks'))
				<!-- Tasks Menu -->
				<li class="dropdown tasks-menu">
					<!-- Menu Toggle Button -->
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="fa fa-flag-o"></i>
						<span class="label label-danger">9</span>
					</a>
					<ul class="dropdown-menu">
						<li class="header">You have 9 tasks</li>
						<li>
							<!-- Inner menu: contains the tasks -->
							<ul class="menu">
								<li><!-- Task item -->
									<a href="#">
										<!-- Task title and progress text -->
										<h3>
											Design some buttons
											<small class="pull-right">20%</small>
										</h3>
										<!-- The progress bar -->
										<div class="progress xs">
											<!-- Change the css width attribute to simulate progress -->
											<div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
												<span class="sr-only">20% Complete</span>
											</div>
										</div>
									</a>
								</li><!-- end task item -->
							</ul>
						</li>
						<li class="footer">
							<a href="#">View all tasks</a>
						</li>
					</ul>
				</li>
				@endif */ ?>
				@if (Auth::guest())
					<li><a href="{{ url('/login') }}">Login</a></li>
					<li><a href="{{ url('/register') }}">Register</a></li>
				@else
@php
$user = Auth::user();
if($user && !empty($user->profile_picture)) {
	$profileImage = asset("storage/images/".$user->profile_picture);
} else {
	$profileImage = asset('/default-profile.jpg');
}
@endphp
					<!-- User Account Menu -->
					<li class="dropdown user user-menu">
						<!-- Menu Toggle Button -->
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<!-- The user image in the navbar-->
							<img src="{{ $profileImage }}" class="user-image" alt="User Image"/>
							<!-- hidden-xs hides the username on small devices so only the image appears. -->
							<span class="hidden-xs">{{ Auth::user()->name }}</span>
						</a>
						<ul class="dropdown-menu">
							<!-- The user image in the menu -->
							<li class="user-header">
								<img src="{{ $profileImage }}" class="img-circle" alt="User Image" />
								<p>
									{{ Auth::user()->name }}
									<?php
									$datec = Auth::user()['created_at'];
									?>
									<small>Member since <?php echo date("M. Y", strtotime($datec)); ?></small>
								</p>
							</li>
							<!-- Menu Body -->
							@if(Entrust::hasRole("SUPER_ADMIN"))
							<li class="user-body">
								<div class="col-xs-12 text-center mb10">
									Shown only to sys-admin
								</div>
								<div class="col-xs-6 text-center mb10">
									<a href="{{ url(config('laraadmin.adminRoute') . '/lacodeeditor') }}"><i class="fa fa-code"></i> <span>Editor</span></a>
								</div>
								<div class="col-xs-6 text-center mb10">
									<a href="{{ url(config('laraadmin.adminRoute') . '/modules') }}"><i class="fa fa-cubes"></i> <span>Modules</span></a>
								</div>
								<div class="col-xs-6 text-center mb10">
									<a href="{{ url(config('laraadmin.adminRoute') . '/la_menus') }}"><i class="fa fa-bars"></i> <span>Menus</span></a>
								</div>
								<div class="col-xs-6 text-center mb10">
									<a href="{{ url(config('laraadmin.adminRoute') . '/la_configs') }}"><i class="fa fa-cogs"></i> <span>Configure</span></a>
								</div>
								<div class="col-xs-6 text-center">
									<a href="{{ url(config('laraadmin.adminRoute') . '/backups') }}"><i class="fa fa-hdd-o"></i> <span>Backups</span></a>
								</div>
							</li>
							@endif
							<!-- Menu Footer-->
							<li class="user-footer">
								<div class="pull-left">
									<a href="{{ url(config('laraadmin.adminRoute') . '/users/') .'/'. Auth::user()->id }}" class="btn btn-default btn-flat">Profile</a>
								</div>
								<div class="pull-right">
									<a href="{{ url('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
								</div>
							</li>
						</ul>
					</li>
				@endif
				@if(LAConfigs::getByKey('show_rightsidebar'))
				<!-- Control Sidebar Toggle Button -->
				{{--<li>
					<a href="#" data-toggle="control-sidebar"><i class="fa fa-comments-o"></i> <span class="label label-warning">10</span></a>

				</li>--}}
				@endif
			</ul>
		</div>