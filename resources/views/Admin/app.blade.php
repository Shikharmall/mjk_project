<!doctype html>
<html lang="en" class="color-sidebar sidebarcolor4 color-header headercolor5">


<!-- Mirrored from codervent.com/amdash/demo/vertical/index2.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 May 2022 06:15:28 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!--favicon-->
	<link rel="icon" href="http://mjk.workfordemo.in/image/logo.png" type="image/png" />
	<!--plugins-->
	<link href="{{asset('assets')}}/plugins/notifications/css/lobibox.min.css" rel="stylesheet"/>
	<link href="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-2.0.2.css" rel="stylesheet"/>
	<link href="{{asset('assets')}}/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
	<link href="{{asset('assets')}}/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
	<!-- loader-->
	<link href="{{asset('assets')}}/css/pace.min.css" rel="stylesheet" />
	<script src="{{asset('assets')}}/js/pace.min.js"></script>
	<!-- Bootstrap CSS -->
	<link href="{{asset('assets')}}/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
	<link href="{{asset('assets')}}/css/app.css" rel="stylesheet">
	<link href="{{asset('assets')}}/css/icons.css" rel="stylesheet">
	<!-- Theme Style CSS -->
	<link rel="stylesheet" href="{{asset('assets')}}/css/dark-theme.css" />
	<link rel="stylesheet" href="{{asset('assets')}}/css/semi-dark.css" />
	<link rel="stylesheet" href="{{asset('assets')}}/css/header-colors.css" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
  <link rel="stylesheet" href="{{asset('assets')}}/css/toastr.css">
	<title>MJK | @yield('title')</title>
	<style>
		html.headercolor5 .topbar {
			background: #2c0b36;
		}
		.sidebar-wrapper .metismenu a .parent-icon {
			font-size: 18px;
			line-height: 1;
		}
		.sidebar-wrapper .metismenu ul a {
			padding: 6px 15px 6px 15px;
			font-size: 13px;
			border: 0;
		}
		.sidebar-wrapper .metismenu a {
			position: relative;
			display: flex;
			align-items: center;
			justify-content: left;
			padding: 8px 15px;
			font-size: 13px;
			color: #5f5f5f;
			outline-width: 0;
			text-overflow: ellipsis;
			overflow: hidden;
			letter-spacing: .5px;
			border: 1px solid #ffffff00;
			transition: all .3s ease-out;
		}
	</style>
</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--sidebar wrapper -->
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
				<li class="menu-label">Manage Data</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-user-circle"></i>
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
						<div class="parent-icon"><i class='lni lni-hospital'></i>
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
				<li class="menu-label">Manage Invoice</li>
				<li>
					<a href="{{route('panel.Invoice.invoice')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Generate Invoice</div>
					</a>
				</li>
				<li>
					<a href="{{route('panel.Invoice.sample-generated-invoice-list')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Sample Generated Invoice</div>
					</a>
				</li>
				<li>
					<a href="{{route('panel.Invoice.kilometer-generated-invoice-list')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Kilometer Generate Invoice</div>
					</a>
				</li>
				<li class="menu-label">Manage Reports</li>
				<li>
					<a href="{{route('panel.Search.nikshay-search')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Nikshay Search</div>
					</a>
				</li>
				<li>
					<a href="{{route('panel.Reports.sample-report')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Sample Reports</div>
					</a>
				</li>
				<li>
					<a href="{{route('panel.Reports.kilometer-report')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Kilometer Reports</div>
					</a>
				</li>
				<li class="menu-label">Manage Collection</li>
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">Collection Details</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.TodayReport.today-collection')}}"><i class="bx bx-right-arrow-alt"></i>Today Collection Report</a>
						</li>
            			<li> <a href="{{route('panel.AllReport.all-collection')}}"><i class="bx bx-right-arrow-alt"></i>All Collection Report</a>
						</li>
					</ul>
				</li> 
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">Submitted Details</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.TodayReport.today-submitted')}}"><i class="bx bx-right-arrow-alt"></i>Today Submitted Report</a>
						</li>
            			<li> <a href="{{route('panel.AllReport.all-submitted')}}"><i class="bx bx-right-arrow-alt"></i>All Submitted Report</a>
						</li>
					</ul>
				</li> 
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">Collected Reports</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.TodayReport.today-collected-report')}}"><i class="bx bx-right-arrow-alt"></i>Today Collected Report</a>
						</li>
            			<li> <a href="{{route('panel.AllReport.all-collected-report')}}"><i class="bx bx-right-arrow-alt"></i>All Collected Report</a>
						</li>
					</ul>
				</li> 
				<li>
					<a href="javascript:;" class="has-arrow">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">Submitted Reports</div>
					</a>
					<ul>
						<li> <a href="{{route('panel.TodayReport.today-submitted-report')}}"><i class="bx bx-right-arrow-alt"></i>Today Submitted Report</a>
						</li>
            			<li> <a href="{{route('panel.AllReport.all-submitted-report')}}"><i class="bx bx-right-arrow-alt"></i>All Submitted Report</a>
						</li>
					</ul>
				</li>
				<!-- <li>
					<a href="{{route('panel.AllReport.sample-report')}}">
						<div class="parent-icon"><i class="bx bx-detail"></i>
						</div>
						<div class="menu-title">Sample Reports</div>
					</a>
				</li> -->
				
				<li class="menu-label">Manage Home</li>
				<li>
					<a href="{{route('panel.District.district')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">District</div>
					</a>
				</li>

				<li>
					<a href="{{route('panel.Specimen.specimen')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Specimen</div>
					</a>
				</li>

				<li>
					<a href="{{route('panel.Test.test')}}">
						<div class="parent-icon"><i class='bx bx-detail'></i>
						</div>
						<div class="menu-title">Test</div>
					</a>
				</li>
			
			</ul>
			<!--end navigation-->
		</div>
		<!--end sidebar wrapper -->
		<!--start header -->
		<header>
			<div class="topbar d-flex align-items-center">
				<nav class="navbar navbar-expand">
					<div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
					</div>
					
					<div class="top-menu ms-auto">
						<ul class="navbar-nav align-items-center">
							<li class="nav-item mobile-search-icon" style="display:none;">
								<a class="nav-link" href="#">	<i class='bx bx-search'></i>
								</a>
							</li>
							<li class="nav-item dropdown dropdown-large" style="display:none;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">	<i class='bx bx-category'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<div class="row row-cols-3 g-3 p-3">
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-cosmic text-white"><i class='bx bx-group'></i>
											</div>
											<div class="app-title">Teams</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-burning text-white"><i class='bx bx-atom'></i>
											</div>
											<div class="app-title">Projects</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-lush text-white"><i class='bx bx-shield'></i>
											</div>
											<div class="app-title">Tasks</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-kyoto text-dark"><i class='bx bx-notification'></i>
											</div>
											<div class="app-title">Feeds</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-blues text-dark"><i class='bx bx-file'></i>
											</div>
											<div class="app-title">Files</div>
										</div>
										<div class="col text-center">
											<div class="app-box mx-auto bg-gradient-moonlit text-white"><i class='bx bx-filter-alt'></i>
											</div>
											<div class="app-title">Alerts</div>
										</div>
									</div>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large" style="display:none;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">7</span>
									<i class='bx bx-bell'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Notifications</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-notifications-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
												ago</span></h6>
													<p class="msg-info">5 new user registered</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Orders <span class="msg-time float-end">2 min
												ago</span></h6>
													<p class="msg-info">You have recived new orders</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class="bx bx-file"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">24 PDF File<span class="msg-time float-end">19 min
												ago</span></h6>
													<p class="msg-info">The pdf files generated</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class="bx bx-send"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Time Response <span class="msg-time float-end">28 min
												ago</span></h6>
													<p class="msg-info">5.1 min avarage time response</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-info text-info"><i class="bx bx-home-circle"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Product Approved <span
												class="msg-time float-end">2 hrs ago</span></h6>
													<p class="msg-info">Your new product has approved</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-danger text-danger"><i class="bx bx-message-detail"></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New Comments <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">New customer comments recived</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-success text-success"><i class='bx bx-check-square'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Your item is shipped <span class="msg-time float-end">5 hrs
												ago</span></h6>
													<p class="msg-info">Successfully shipped your item</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-primary text-primary"><i class='bx bx-user-pin'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">New 24 authors<span class="msg-time float-end">1 day
												ago</span></h6>
													<p class="msg-info">24 new authors joined last week</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="notify bg-light-warning text-warning"><i class='bx bx-door-open'></i>
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Defense Alerts <span class="msg-time float-end">2 weeks
												ago</span></h6>
													<p class="msg-info">45% less alerts last 4 weeks</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Notifications</div>
									</a>
								</div>
							</li>
							<li class="nav-item dropdown dropdown-large" style="display:none;">
								<a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
									<i class='bx bx-comment'></i>
								</a>
								<div class="dropdown-menu dropdown-menu-end">
									<a href="javascript:;">
										<div class="msg-header">
											<p class="msg-header-title">Messages</p>
											<p class="msg-header-clear ms-auto">Marks all as read</p>
										</div>
									</a>
									<div class="header-message-list">
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-1.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Daisy Anderson <span class="msg-time float-end">5 sec
												ago</span></h6>
													<p class="msg-info">The standard chunk of lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-2.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Althea Cabardo <span class="msg-time float-end">14
												sec ago</span></h6>
													<p class="msg-info">Many desktop publishing packages</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-3.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Oscar Garner <span class="msg-time float-end">8 min
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-4.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Katherine Pechon <span class="msg-time float-end">15
												min ago</span></h6>
													<p class="msg-info">Making this the first true generator</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-5.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Amelia Doe <span class="msg-time float-end">22 min
												ago</span></h6>
													<p class="msg-info">Duis aute irure dolor in reprehenderit</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-6.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Cristina Jhons <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">The passage is attributed to an unknown</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-7.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">James Caviness <span class="msg-time float-end">4 hrs
												ago</span></h6>
													<p class="msg-info">The point of using Lorem</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-8.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Peter Costanzo <span class="msg-time float-end">6 hrs
												ago</span></h6>
													<p class="msg-info">It was popularised in the 1960s</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-9.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">David Buckley <span class="msg-time float-end">2 hrs
												ago</span></h6>
													<p class="msg-info">Various versions have evolved over</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-10.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Thomas Wheeler <span class="msg-time float-end">2 days
												ago</span></h6>
													<p class="msg-info">If you are going to use a passage</p>
												</div>
											</div>
										</a>
										<a class="dropdown-item" href="javascript:;">
											<div class="d-flex align-items-center">
												<div class="user-online">
													<img src="assets/images/avatars/avatar-11.png" class="msg-avatar" alt="user avatar">
												</div>
												<div class="flex-grow-1">
													<h6 class="msg-name">Johnny Seitz <span class="msg-time float-end">5 days
												ago</span></h6>
													<p class="msg-info">All the Lorem Ipsum generators</p>
												</div>
											</div>
										</a>
									</div>
									<a href="javascript:;">
										<div class="text-center msg-footer">View All Messages</div>
									</a>
								</div>
							</li>
						</ul>
					</div>
					<div class="user-box dropdown">
						<a class="d-flex align-items-center" onclick="Swal.fire({
                                    title: 'Do you want to logout?',
                                    showDenyButton: true,
                                    showCancelButton: true,
                                    confirmButtonColor: '#CF2228',
                                    cancelButtonColor: '#363636',
                                    confirmButtonText: `Yes`,
                                    denyButtonText: `Don't Logout`,
                                    }).then((result) => {
                                    if (result.value) {
                                    location.href='{{route('panel.auth.logout')}}';
                                    } else{
                                    Swal.fire('Canceled', '', 'info')
                                    }
                                    })" style="color:white; cursor:pointer; margin-left: 25px;">
									
									<!-- <div style="display: -webkit-box;background: none;border: none;color: white;">
									<img src="assets/images/avatars/avatar-2.png" class="user-img" alt="user avatar">
									<div class="user-info ps-3">
										<p class="user-name mb-0">Pauline Seitz</p>
										<p class="designattion mb-0">Web Designer</p>
									</div> -->
									Log Out
						</a>
						<!-- <ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class="bx bx-cog"></i><span>Settings</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-dollar-circle'></i><span>Earnings</span></a>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-download'></i><span>Downloads</span></a>
							</li>
							<li>
								<div class="dropdown-divider mb-0"></div>
							</li>
							<li><a class="dropdown-item" href="javascript:;"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
							</li>
						</ul> -->
					</div>
				</nav>
			</div>
		</header>

        @yield('content')


        
	
		<!--start overlay-->
		<div class="overlay toggle-icon"></div>
		<!--end overlay-->
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
		<footer class="page-footer">
			<p class="mb-0">Copyright © 2022 MJK. All right reserved.</p>
		</footer>
	</div>
	<!--end wrapper-->
	<!-- Bootstrap JS -->
	<script src="{{asset('assets')}}/js/bootstrap.bundle.min.js"></script>
	<!--plugins-->
	<script src="{{asset('assets')}}/js/jquery.min.js"></script>
	<script src="{{asset('assets')}}/plugins/simplebar/js/simplebar.min.js"></script>
	<script src="{{asset('assets')}}/plugins/metismenu/js/metisMenu.min.js"></script>
	<script src="{{asset('assets')}}/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
	<script src="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-2.0.2.min.js"></script>
    <script src="{{asset('assets')}}/plugins/vectormap/jquery-jvectormap-world-mill-en.js"></script>
	<script src="{{asset('assets')}}/plugins/chartjs/js/Chart.min.js"></script>
	<script src="{{asset('assets')}}/plugins/chartjs/js/Chart.extension.js"></script>
	<script src="{{asset('assets')}}/plugins/sparkline-charts/jquery.sparkline.min.js"></script>
	<!--notification js -->
	<script src="{{asset('assets')}}/plugins/notifications/js/lobibox.min.js"></script>
	<script src="{{asset('assets')}}/plugins/notifications/js/notifications.min.js"></script>
	<script src="{{asset('assets')}}/js/index2.js"></script>

	<script src="{{asset('assets')}}/plugins/datatable/js/jquery.dataTables.min.js"></script>
	<script src="{{asset('assets')}}/plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
	<script>
		$(document).ready(function() {
			$('#example').DataTable();
		  } );
	</script>
	<script>
		$(document).ready(function() {
			var table = $('#example2').DataTable( {
				lengthChange: false,
				buttons: [ 'copy', 'excel', 'pdf', 'print']
			} );
		 
			table.buttons().container()
				.appendTo( '#example2_wrapper .col-md-6:eq(0)' );
		} );
	</script>
	<!--app JS-->
	<script src="{{asset('assets')}}/js/app.js"></script>
	<script src="{{asset('assets')}}/js/sweet_alert.js"></script>
  <script src="{{asset('assets')}}/js/toastr.js"></script>
    <!--end::Page Scripts-->
    {!! Toastr::message() !!}

@if ($errors->any())
    <script>
        @foreach($errors->all() as $error)
        toastr.error('{{$error}}', Error, {
            CloseButton: true,
            ProgressBar: true
        });
        @endforeach
    </script>
@endif
</body>
</html>