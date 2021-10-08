<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Velonic - Responsive Bootstrap 4 Admin Dashboard Template</title>
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App favicon -->
        <link rel="shortcut icon" href="assets/images/favicon.ico">

        <!-- Plugins css-->
        <link href="<?= base_url() ?>assets/plugins/bootstrap-tagsinput/css/bootstrap-tagsinput.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/plugins/bootstrap-touchspin/css/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
        <link href="<?= base_url() ?>assets/plugins/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
        <link href="<?= base_url() ?>assets/plugins/switchery/switchery.min.css" rel="stylesheet">

        <!-- App css -->
        <link href="<?= base_url() ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/icons.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/metismenu.min.css" rel="stylesheet" type="text/css" />
        <link href="<?= base_url() ?>assets/css/app.css" rel="stylesheet" type="text/css" />
        <!-- your custom css -->
        <link href="<?= base_url() ?>assets/css/style.css" rel="stylesheet" type="text/css" />

        <script src="<?= base_url() ?>assets/js/modernizr.min.js"></script>

    </head>


    <body>

        <!-- Begin page -->
        <div id="wrapper">

            <!-- Top Bar Start -->
            <div class="topbar">

                <!-- LOGO -->
                <div class="topbar-left">
                    <a href="index.html" class="logo">
                        <span>
                            <img src="assets/images/logo.png" alt="" height="18">
                        </span>
                        <i>
                            <img src="assets/images/logo_sm.png" alt="" height="22">
                        </i>
                    </a>
                </div>

                <nav class="navbar-custom">

                    <ul class="list-inline float-right mb-0">
                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-email-outline noti-icon waves-light waves-effect"></i>
                            </a>
                            <span class="badge badge-purple noti-icon-badge">3</span>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-menu-right dropdown-arrow dropdown-lg">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="m-0"><span class="float-right"><a href="" class="text-dark"><small>Clear All</small></a> </span>Chat</h5>
                                </div>

                                <div class="slimscroll" style="max-height: 230px;">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon"><img src="assets/images/users/avatar-2.jpg" class="img-fluid rounded-circle" alt="" /> </div>
                                        <p class="notify-details">Cristina Pride</p>
                                        <p class="text-muted font-13 mb-0 user-msg">Hi, How are you? What about our next meeting</p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon"><img src="assets/images/users/avatar-3.jpg" class="img-fluid rounded-circle" alt="" /> </div>
                                        <p class="notify-details">Sam Garret</p>
                                        <p class="text-muted font-13 mb-0 user-msg">Yeah everything is fine</p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon"><img src="assets/images/users/avatar-4.jpg" class="img-fluid rounded-circle" alt="" /> </div>
                                        <p class="notify-details">Karen Robinson</p>
                                        <p class="text-muted font-13 mb-0 user-msg">Wow that's great</p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon"><img src="assets/images/users/avatar-5.jpg" class="img-fluid rounded-circle" alt="" /> </div>
                                        <p class="notify-details">Sherry Marshall</p>
                                        <p class="text-muted font-13 mb-0 user-msg">Hi, How are you? What about our next meeting</p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon"><img src="assets/images/users/avatar-6.jpg" class="img-fluid rounded-circle" alt="" /> </div>
                                        <p class="notify-details">Shawn Millard</p>
                                        <p class="text-muted font-13 mb-0 user-msg">Yeah everything is fine</p>
                                    </a>
                                </div>

                                <!-- All-->
                                <a href="javascript:void(0);" class="dropdown-item text-center text-dark notify-item notify-all">
                                    View all <i class="fi-arrow-right"></i>
                                </a>

                            </div>
                        </li>

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class="mdi mdi-bell-outline noti-icon waves-light waves-effect"></i>
                            </a>
                            <span class="badge badge-pink noti-icon-badge">4</span>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-menu-right dropdown-arrow dropdown-lg">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="m-0"><span class="float-right"><a href="" class="text-dark"><small>Clear All</small></a> </span>Notification</h5>
                                </div>

                                <div class="slimscroll" style="max-height: 230px;">
                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-success"><i class="mdi mdi-comment-account-outline"></i></div>
                                        <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">1 min ago</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-info"><i class="mdi mdi-account-plus"></i></div>
                                        <p class="notify-details">New user registered.<small class="text-muted">5 hours ago</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-danger"><i class="mdi mdi-heart"></i></div>
                                        <p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">3 days ago</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-warning"><i class="mdi mdi-comment-account-outline"></i></div>
                                        <p class="notify-details">Caleb Flakelar commented on Admin<small class="text-muted">4 days ago</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-purple"><i class="mdi mdi-account-plus"></i></div>
                                        <p class="notify-details">New user registered.<small class="text-muted">7 days ago</small></p>
                                    </a>

                                    <!-- item-->
                                    <a href="javascript:void(0);" class="dropdown-item notify-item">
                                        <div class="notify-icon text-primary"><i class="mdi mdi-heart"></i></div>
                                        <p class="notify-details">Carlos Crouch liked <b>Admin</b><small class="text-muted">13 days ago</small></p>
                                    </a>
                                </div>

                                <!-- All-->
                                <a href="javascript:void(0);" class="dropdown-item text-center text-dark notify-item notify-all">
                                    View all <i class="fi-arrow-right"></i>
                                </a>

                            </div>
                        </li>

                        <li class="list-inline-item dropdown notification-list">
                            <a class="nav-link dropdown-toggle nav-user" data-toggle="dropdown" href="#" role="button"
                               aria-haspopup="false" aria-expanded="false">
                                <i class="noti-icon"><img src="assets/images/users/avatar-1.jpg" alt="user" class="img-fluid rounded-circle"></i>
                                <span class="profile-username ml-2 text-dark">Thompson </span> <span class="mdi mdi-menu-down text-dark"></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-animated dropdown-menu-right profile-dropdown ">
                                <!-- item-->
                                <div class="dropdown-item noti-title">
                                    <h5 class="text-overflow"><small>Welcome ! John</small> </h5>
                                </div>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="mdi mdi-account-circle"></i> <span>Profile</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="mdi mdi-settings"></i> <span>Settings</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="mdi mdi-lock-open"></i> <span>Lock Screen</span>
                                </a>

                                <!-- item-->
                                <a href="javascript:void(0);" class="dropdown-item notify-item">
                                    <i class="mdi mdi-power"></i> <span>Logout</span>
                                </a>

                            </div>
                        </li>

                    </ul>

                    <ul class="list-inline menu-left mb-0">
                        <li class="float-left">
                            <button class="button-menu-mobile open-left waves-light waves-effect">
                                <i class="mdi mdi-menu"></i>
                            </button>
                        </li>
                        <li class="hide-phone app-search">
                            <form role="search" class="">
                                <input type="text" placeholder="Search..." class="form-control">
                                <a href=""><i class="fa fa-search"></i></a>
                            </form>
                        </li>
                    </ul>

                </nav>

            </div>
            <!-- Top Bar End -->


            <!-- ========== Left Sidebar Start ========== -->
            <div class="left side-menu">
                <div class="slimscroll-menu" id="remove-scroll">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Navigation</li>
                            <li>
                                <a href="javascript: void(0);">
                                    <i class="ion-md-speedometer"></i><span class="badge badge-success float-right">2</span> <span> Dashboard </span>
                                </a>
                                <ul class="nav-second-level" aria-expanded=false>
                                    <li><a href="index.html">Dashboard 1</a></li>
                                    <li><a href="dashboard-2.html">Dashboard 2</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-basket"></i> <span> UI Elements </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="ui-typography.html">Typography</a></li>
                                    <li><a href="ui-buttons.html">Buttons</a></li>
                                    <li><a href="ui-fontawesome.html">Font Awesome Icons</a></li>
                                    <li><a href="ui-materialdesign.html">Material Design Icons</a></li>
                                    <li><a href="ui-ionicons.html">Ion Icons</a></li>
                                    <li><a href="ui-cards.html">Cards</a></li>
                                    <li><a href="ui-tabs-accordions.html">Tabs &amp; Accordions</a></li>
                                    <li><a href="ui-modals.html">Modals</a></li>
                                    <li><a href="ui-bootstrap-ui.html">BS Elements</a></li>
                                    <li><a href="ui-progressbars.html">Progress Bars</a></li>
                                    <li><a href="ui-notification.html">Notification</a></li>
                                    <li><a href="ui-sweet-alert.html">Sweet-Alert</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-ios-apps"></i> <span> Components </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="components-grid.html">Grid</a></li>
                                    <li><a href="components-portlets.html">Portlets</a></li>
                                    <li><a href="components-widgets.html">Widgets</a></li>
                                    <li><a href="components-nestable-list.html">Nesteble</a></li>
                                    <li><a href="components-calendar.html">Calendar</a></li>
                                    <li><a href="components-range-sliders.html">Range Slider</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-paper"></i><span class="badge badge-danger float-right">9</span> <span> Forms </span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="forms-elements.html">General Elements</a></li>
                                    <li><a href="forms-validation.html">Form Validation</a></li>
                                    <li><a href="forms-advanced.html">Advanced Form</a></li>
                                    <li><a href="forms-wizard.html">Form Wizard</a></li>
                                    <li><a href="forms-editor.html">WYSIWYG Editor</a></li>
                                    <li><a href="forms-code-editor.html">Code Editors</a></li>
                                    <li><a href="forms-uploads.html">Multiple File Upload</a></li>
                                    <li><a href="forms-image-crop.html">Image Crop</a></li>
                                    <li><a href="forms-xeditable.html">X-Editable</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-ios-list"></i><span> Tables </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="tables-basic.html">Basic Tables</a></li>
                                    <li><a href="tables-datatable.html">Data Table</a></li>
                                    <li><a href="tables-editable.html">Editable Table</a></li>
                                    <li><a href="tables-responsive.html">Responsive Table</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-pie"></i><span> Charts </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="charts-morris.html">Morris Chart</a></li>
                                    <li><a href="charts-chartjs.html">Chartjs</a></li>
                                    <li><a href="charts-flot.html">Flot Chart</a></li>
                                    <li><a href="charts-rickshaw.html">Rickshaw Chart</a></li>
                                    <li><a href="charts-peity.html">Peity Chart</a></li>
                                    <li><a href="charts-c3.html">C3 Chart</a></li>
                                    <li><a href="charts-other.html">Other Chart</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-mail"></i><span class="badge badge-warning float-right">12</span> <span> Mail </span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="email-inbox.html">Inbox</a></li>
                                    <li><a href="email-compose.html">Compose Mail</a></li>
                                    <li><a href="email-read.html">View Mail</a></li>
                                    <li><a href="email-templates.html">Email Templates</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-map"></i> <span> Maps </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="maps-gmap.html"> Google Map</a></li>
                                    <li><a href="maps-vector.html"> Vector Map</a></li>
                                </ul>
                            </li>

                            <li>
                                <a href="javascript: void(0);"><i class="ion-md-copy"></i> <span> Pages </span> <span class="menu-arrow"></span></a>
                                <ul class="nav-second-level" aria-expanded="false">
                                    <li><a href="pages-profile.html">Profile</a></li>
                                    <li><a href="pages-timeline.html">Timeline</a></li>
                                    <li><a href="pages-invoice.html">Invoice</a></li>
                                    <li><a href="pages-contact.html">Contact-list</a></li>
                                    <li><a href="pages-login.html">Login</a></li>
                                    <li><a href="pages-register.html">Register</a></li>
                                    <li><a href="pages-recoverpw.html">Recover Password</a></li>
                                    <li><a href="pages-lock-screen.html">Lock Screen</a></li>
                                    <li><a href="pages-blank.html">Blank Page</a></li>
                                    <li><a href="pages-404.html">404 Error</a></li>
                                    <li><a href="pages-404_alt.html">404 alt</a></li>
                                    <li><a href="pages-500.html">500 Error</a></li>
                                </ul>
                            </li>

                        </ul>

                    </div>
                    <!-- Sidebar -->
                    <div class="clearfix"></div>

                </div>
                <!-- Sidebar -left -->

            </div>
            <!-- Left Sidebar End -->



            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="content-page">
                <!-- Start content -->
                <div class="content">
                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <h4 class="page-title float-left">Advanced Form</h4>

                                    <ol class="breadcrumb float-right">
                                        <li class="breadcrumb-item"><a href="#">Velonic</a></li>
                                        <li class="breadcrumb-item"><a href="#">Forms</a></li>
                                        <li class="breadcrumb-item active">Advanced Form</li>
                                    </ol>

                                    <div class="clearfix"></div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->


                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0 mb-3">Tags Input</h4>
    
                                        <p>Input Tags</p>
                                        <p class="text-muted m-b-20 font-14">
                                            Just add <code>data-role="tagsinput"</code> to your input field to automatically change it to a tags input field.
                                        </p>
                                        <div class="tags-default">
                                            <input type="text" value="Amsterdam,Washington,Sydney" data-role="tagsinput" placeholder="add tags"/>
                                        </div>
    
                                        <p class="m-t-30">True multi value</p>
                                        <p class="text-muted m-b-20 font-14">
                                            Use a <code>&lt;select multiple /&gt;</code> as your input element for a tags input, to gain true multivalue support.
                                            Instead of a comma separated string, the values will be set in an array. Existing <code>&lt;option /&gt;</code>
                                            elements will automatically be set as tags. This makes it also possible to create tags containing a comma.
                                        </p>
                                        <div class="m-b-0">
                                            <select multiple data-role="tagsinput">
                                                <option value="Amsterdam">Amsterdam</option>
                                                <option value="Washington">Washington</option>
                                                <option value="Sydney">Sydney</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0 mb-3">Switchery</h4>
    
                                        <p>Basic</p>
                                        <p class="text-muted m-b-20 font-14">
                                            Add an attribute <code>
                                            data-plugin="switchery" data-color="@colors"</code>
                                            to your input element and it will be converted into switch.
                                        </p>
    
                                        <div class="switchery-demo">
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#039cfd"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#f1b53d"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#1bb99a"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#ff5d48"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#3db9dc"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#2b3d51"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#9261c6"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#ff7aa3"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#98a6ad"/>
                                        </div>
    
    
                                        <p class="m-t-30">Sizes & Secondary color</p>
                                        <p class="text-muted m-b-30 font-14">
                                            Add an attribute <code>
                                            data-size="small",data-size="large"</code>
                                            to your input element and it will be converted into switch.
                                            Add an attribute <code>
                                            data-color="@color" data-secondary-color="@color"</code>
                                            to your input element and it will be converted into switch.
                                        </p>
    
                                        <div class="switchery-demo">
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#64b0f2" data-size="small"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#ff7aa3"/>
                                            <input type="checkbox" checked data-plugin="switchery" data-color="#2b3d51" data-size="large"/>
                                            <input type="checkbox" data-plugin="switchery" data-color="#1bb99a" data-secondary-color="#ff5d48" />
                                            <input type="checkbox" data-plugin="switchery" data-color="#9261c6"  data-secondary-color="#ff7aa3" checked />
                                        </div>
                                    </div> <!-- end card-box -->
                                </div>
                            </div> <!-- end col -->
                        </div><!-- end row -->

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="m-t-0 header-title"><b>Bootstrap TouchSpin</b></h4>
                                        <p class="text-muted font-14 m-b-20">
                                            You can limit the number of elements you are allowed to select via the
                                            <code>
                                                data-max-option
                                            </code>
                                            attribute. It also works for option groups.
                                        </p>
    
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="">
                                                    <form>
    
                                                        <div class="form-group">
                                                            <p>Using data attributes</p>
                                                            <input id="demo0" type="text" value="55" name="demo0" data-bts-min="0" data-bts-max="100" data-bts-init-val="" data-bts-step="1" data-bts-decimal="0" data-bts-step-interval="100" data-bts-force-step-divisibility="round" data-bts-step-interval-delay="500" data-bts-prefix="" data-bts-postfix="" data-bts-prefix-extra-class="" data-bts-postfix-extra-class="" data-bts-booster="true" data-bts-boostat="10" data-bts-max-boosted-step="false" data-bts-mousewheel="true" data-bts-button-down-class="btn btn-secondary" data-bts-button-up-class="btn btn-secondary"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <p>Example with postfix (large)</p>
                                                            <input id="demo1" type="text" value="55" name="demo1">
                                                        </div>
                                                        <div class="form-group m-b-0">
                                                            <p>With prefix </p>
                                                            <input id="demo2" type="text" value="0" name="demo2" class=" form-control">
                                                        </div>
    
                                                    </form>
                                                </div>
                                            </div>
    
                                            <div class="col-md-6">
                                                <div class="">
                                                    <form>
                                                        <div class="form-group">
                                                            <p>Init with empty value:</p>
                                                            <input id="demo3" type="text" value="" name="demo3">
                                                        </div>
                                                        <div class="form-group">
                                                            <p>Value attribute is not set (applying settings.initval)</p>
                                                            <input id="demo3_21" type="text" value="" name="demo3_21">
                                                        </div>
                                                        <div class="form-group">
                                                            <p>Value is set explicitly to 33 (skipping settings.initval) </p>
                                                            <input id="demo3_22" type="text" value="33" name="demo3_22">
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div> <!-- end row -->
                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->


                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0">Timepicker</h4>
                                        <p class="text-muted font-14 m-b-25">
                                            Easily select a time for a text input using your mouse or keyboards arrow keys.
                                        </p>
    
                                        <div class="form-group">
                                            <label>Default Time Picker</label>
                                            <div class="input-group">
                                                <input id="timepicker" type="text" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                                </div>
                                            </div><!-- input-group -->
                                        </div>
    
                                        <div class="form-group">
                                            <label>24 Hour Mode Time Picker</label>
                                            <div class="input-group">
                                                <input id="timepicker2" type="text" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                                </div>
                                            </div><!-- input-group -->
                                        </div>
    
                                        <div class="form-group">
                                            <label>Specify a step for the minute field</label>
                                            <div class="input-group">
                                                <input id="timepicker3" type="text" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text"><i class="mdi mdi-clock"></i></span>
                                                </div>
                                            </div><!-- input-group -->
                                        </div>
    
                                    </div> <!-- end card-box -->
                                </div>
                            </div> <!-- end col -->

                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0">Colorpicker</h4>
                                        <p class="text-muted font-14 m-b-25">
                                            Add color picker to field or to any other element.
                                        </p>
    
                                        <form action="#">
                                            <div class="form-group">
                                                <label>Default</label>
                                                <input type="text" class="colorpicker-default form-control" value="#8fff00">
                                            </div>
                                            <div class="form-group">
                                                <label>RGBA</label>
                                                <input type="text" class="colorpicker-rgba form-control" value="rgb(0,194,255,0.78)" data-color-format="rgba">
                                            </div>
                                            <div class="form-group">
                                                <label>As Component</label>
                                                <div data-color-format="rgb" data-color="rgb(255, 146, 180)" class="colorpicker-default input-group">
                                                    <input type="text" readonly="readonly" value="" class="form-control">
                                                    <span class="input-group-append add-on">
                                                        <button class="btn btn-white" type="button">
                                                            <i style="background-color: rgb(124, 66, 84);margin-top: 2px;"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                            </div>
                                        </form>
                                    </div> <!-- end card-box -->
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0">Date Picker</h4>
                                        <p class="text-muted font-14 m-b-25">
                                            The datepicker is tied to a standard form input field. Click on the input to open
                                            an interactive calendar in a small overlay. Click elsewhere on the page or hit the Esc
                                            key to close. If a date is chosen, feedback is shown as the input's value.
                                        </p>
    
                                        <div class="row">
                                            <div class="col-lg-8">
    
                                                <div class="">
                                                    <form action="#">
                                                        <div class="form-group">
                                                            <label>Default Functionality</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div><!-- input-group -->
                                                            </div>
                                                        </div>
    
                                                        <div class="form-group">
                                                            <label>Auto Close</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-autoclose">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div><!-- input-group -->
                                                            </div>
                                                        </div>
    
                                                        <div class="form-group">
                                                            <label>Multiple Date</label>
                                                            <div>
                                                                <div class="input-group">
                                                                    <input type="text" class="form-control" placeholder="mm/dd/yyyy" id="datepicker-multiple-date">
                                                                    <div class="input-group-append">
                                                                        <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                                                    </div>
                                                                </div><!-- input-group -->
                                                            </div>
                                                        </div>
    
                                                        <div class="form-group">
                                                            <label>Date Range</label>
                                                            <div>
                                                                <div class="input-daterange input-group" id="date-range">
                                                                    <input type="text" class="form-control" name="start" />
                                                                    <input type="text" class="form-control" name="end" />
                                                                </div>
                                                            </div>
                                                        </div>
    
                                                    </form>
                                                </div>
                                            </div>
    
                                            <div class="col-lg-4">
    
                                                <div class="p-20">
    
                                                    <label>Display Inline</label>
                                                    <div class="center-block" style="margin: 10px auto">
                                                        <div id="datepicker-inline" class=""></div>
                                                    </div><!-- input-group -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- end row -->    


                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title m-t-0 m-b-20">Select2</h4>
    
                                        <p>Single Select</p>
                                        <p class="text-muted m-b-15 font-14">
                                            Select2 can take a regular select box like this...
                                        </p>
    
                                        <select class="form-control select2">
                                            <option>Select</option>
                                            <optgroup label="Alaskan/Hawaiian Time Zone">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </optgroup>
                                            <optgroup label="Pacific Time Zone">
                                                <option value="CA">California</option>
                                                <option value="NV">Nevada</option>
                                                <option value="OR">Oregon</option>
                                                <option value="WA">Washington</option>
                                            </optgroup>
                                            <optgroup label="Mountain Time Zone">
                                                <option value="AZ">Arizona</option>
                                                <option value="CO">Colorado</option>
                                                <option value="ID">Idaho</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="UT">Utah</option>
                                                <option value="WY">Wyoming</option>
                                            </optgroup>
                                            <optgroup label="Central Time Zone">
                                                <option value="AL">Alabama</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TX">Texas</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="WI">Wisconsin</option>
                                            </optgroup>
                                            <optgroup label="Eastern Time Zone">
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="IN">Indiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="OH">Ohio</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WV">West Virginia</option>
                                            </optgroup>
                                        </select>
    
                                        <p class="m-t-30">Multiple Select<p>
                                        <p class="text-muted m-b-15 font-14">
                                            Select2 can take a regular select box like this...
                                        </p>
    
                                        <select class="select2 form-control select2-multiple" multiple="multiple" multiple data-placeholder="Choose ...">
                                            <optgroup label="Alaskan/Hawaiian Time Zone">
                                                <option value="AK">Alaska</option>
                                                <option value="HI">Hawaii</option>
                                            </optgroup>
                                            <optgroup label="Pacific Time Zone">
                                                <option value="CA">California</option>
                                                <option value="NV">Nevada</option>
                                                <option value="OR">Oregon</option>
                                                <option value="WA">Washington</option>
                                            </optgroup>
                                            <optgroup label="Mountain Time Zone">
                                                <option value="AZ">Arizona</option>
                                                <option value="CO">Colorado</option>
                                                <option value="ID">Idaho</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="UT">Utah</option>
                                                <option value="WY">Wyoming</option>
                                            </optgroup>
                                            <optgroup label="Central Time Zone">
                                                <option value="AL">Alabama</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TX">Texas</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="WI">Wisconsin</option>
                                            </optgroup>
                                            <optgroup label="Eastern Time Zone">
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="IN">Indiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="OH">Ohio</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WV">West Virginia</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title mb-3 mt-0">Input Masks</h4>
    
                                        <form action="#">
                                            <div class="form-group">
                                                <label>Tax ID</label>
                                                <input type="text" placeholder="" data-mask="99-9999999" class="form-control">
                                                <span class="font-14 text-muted">99-9999999</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" placeholder="" data-mask="(999) 999-9999" class="form-control">
                                                <span class="font-14 text-muted">(999) 999-9999</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Currency</label>
                                                <input type="text" placeholder="" data-mask="$ 999,999,999.99" class="form-control">
                                                <span class="font-14 text-muted">$ 999,999,999.99</span>
                                            </div>
                                            <div class="form-group">
                                                <label>Date</label>
                                                <input type="text" placeholder="" data-mask="99/99/9999" class="form-control">
                                                <span class="font-14 text-muted">dd/mm/yyyy</span>
                                            </div>
                                            <div class="form-group m-b-0">
                                                <label>Date 2</label>
                                                <input type="text" placeholder="" data-mask="99-99-9999" class="form-control">
                                                <span class="font-14 text-muted">dd-mm-yyyy</span>
                                            </div>
    
                                        </form>
        
                                    </div>
                                </div>
                            </div> <!-- end col -->

                        </div>
                        <!-- end row -->

                    </div> <!-- container -->

                </div> <!-- content -->

                <footer class="footer text-right">
                    2015 - 2018 © Velonic - Coderthemes.com
                </footer>

            </div>


            <!-- ============================================================== -->
            <!-- End Right content here -->
            <!-- ============================================================== -->


        </div>
        <!-- END wrapper -->



        <!-- jQuery  -->
        <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script src="<?= base_url() ?>assets/js/bootstrap.bundle.min.js"></script>
        <script src="<?= base_url() ?>assets/js/metisMenu.min.js"></script>
        <script src="<?= base_url() ?>assets/js/waves.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.slimscroll.js"></script>

        <script src="<?= base_url()?>assets/plugins/switchery/switchery.min.js"></script>
        <script src="<?= base_url()?>assets/plugins/moment/moment.js"></script>
        <script src="<?= base_url()?>assets/plugins/timepicker/bootstrap-timepicker.js"></script>
        <script src="<?= base_url()?>assets/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js"></script>
        <script src="<?= base_url()?>assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="<?= base_url()?>assets/plugins/bootstrap-tagsinput/js/bootstrap-tagsinput.min.js"></script>
        <script src="<?= base_url()?>assets/plugins/bootstrap-touchspin/js/jquery.bootstrap-touchspin.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/plugins/select2/js/select2.min.js" type="text/javascript"></script>
        <script src="<?= base_url()?>assets/plugins/bootstrap-inputmask/bootstrap-inputmask.min.js" type="text/javascript"></script>

        <!-- Init Js file -->
        <script type="text/javascript" src="assets/pages/jquery.form-advanced.init.js"></script>

        <!-- App js -->
        <script src="<?= base_url() ?>assets/js/jquery.core.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery.app.js"></script>

    </body>
</html>