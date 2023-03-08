<div class="sidebar-wrapper" data-simplebar="true">
			<div class="sidebar-header">
				<div>
					<img src="http://mjk.workfordemo.in/image/logo.png" class="logo-icon" alt="logo icon">
				</div>
				<div>
					<h4 class="logo-text">MJK</h4>
				</div>
				<div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
				</div>
			</div>
			<!--navigation-->
			<ul class="metismenu" id="menu">
				<li>
					<a href="{{route('panel.dashboard')}}">
						<div class="parent-icon"><i class='bx bx-home-circle'></i>
						</div>
						<div class="menu-title">Dashboard</div>
					</a>
				</li>

				
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class='bx bx-user-circle'></i>
						</div>
						<div class="menu-title">Register Staff</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.User.list')}}"><i class="bx bx-right-arrow-alt"></i>All Staff</a>
						</li>
					</ul>
				</li>
       

        		<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="lni lni-hospital"></i>
						</div>
						<div class="menu-title">Hospital</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.Hospital.hospital')}}"><i class="bx bx-right-arrow-alt"></i>Add Hospital</a>
						</li>
            			<li> <a href="{{route('panel.Hospital.list')}}"><i class="bx bx-right-arrow-alt"></i>All Hospital</a>
						</li>
					</ul>
				</li>

        		<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">District</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.District.district')}}"><i class="bx bx-right-arrow-alt"></i>Add District</a>
						</li>
            			<li> <a href="{{route('panel.District.list')}}"><i class="bx bx-right-arrow-alt"></i>All District</a>
						</li>
					</ul>
				</li>

        		<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="lni lni-hospital"></i>
						</div>
						<div class="menu-title">Lab</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.Lab.lab')}}"><i class="bx bx-right-arrow-alt"></i>Add Lab</a>
						</li>
            			<li> <a href="{{route('panel.Lab.list')}}"><i class="bx bx-right-arrow-alt"></i>All Lab</a>
						</li>
					</ul>
				</li> 
		
			</ul>
			<!--end navigation-->
</div>