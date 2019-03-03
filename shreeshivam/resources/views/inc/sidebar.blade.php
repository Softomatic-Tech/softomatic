a<section>
        <!-- Left Sidebar -->
        <aside id="leftsidebar" class="sidebar">
            <!-- User Info -->
            <div class="user-info">
                <div class="image">
                    <img src="images/user.png" width="48" height="48" alt="User" />
                </div>
                <div class="info-container">
                    <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><?php echo Session::get('username'); ?></div>
                    <div class="email"><?php echo Session::get('useremail'); ?></div>
                    <div class="btn-group user-helper-dropdown">
                        <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                        <ul class="dropdown-menu pull-right">
                            <li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li>
                            <!-- <li role="separator" class="divider"></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">shopping_cart</i>Sales</a></li>
                            <li><a href="javascript:void(0);"><i class="material-icons">favorite</i>Likes</a></li>
                            <li role="separator" class="divider"></li> -->
                            <li><a href="logout"><i class="material-icons">input</i>Sign Out</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- #User Info -->
            <!-- Menu -->
            <div class="menu">
                <ul class="list">
                    <li class="header">MAIN NAVIGATION</li>

            @if(session('role')=='hr' || session('role')=='admin')
                @if('master'== Request::path())
                    <li class="active ">
                @else
                    <li>
                 @endif
                        <a href="master">
                            <i class="material-icons">settings</i>
                            <span>Master Setting</span>
                        </a>
                    </li>

            @endif

                @if('dashboard'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                    <!-- <li class="active"> -->
                        <a href="dashboard">
                            <i class="material-icons">dashboard</i>
                            <span>Dashboard</span>
                        </a>
                    </li>
            
             @if(session('role')=='hr' || session('role')=='admin')
                @if('zoneanalysis'== Request::path() || 'employeeanalysis'== Request::path() || 'brandanalysis' == Request::path())
                        <li class="active ">
                    @else
                        <li>
                    @endif
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">insert_chart</i>
                            <span>Analysis</span>
                        </a>
                        <ul class="ml-menu">

                        {{-- @if(session('role')!='sales' && session('role')!='employee') --}}

                            @if('zoneanalysis'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                <a href="zoneanalysis">
                                    <i class="material-icons">show_chart</i>
                                    <span>Zone Analysis</span>
                                </a>
                            </li>
                             @if('employeeanalysis'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                <a href="employeeanalysis">
                                    <i class="material-icons">show_chart</i>
                                    <span>Employee Analysis</span>
                                </a>
                            </li>
                        {{-- @endif --}}

                        @if('brandanalysis'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="brandanalysis">
                                    <i class="material-icons">show_chart</i>
                                    <span>Brand Wise Analysis</span>
                                </a>
                            </li>
                        </ul>
                    </li>
            @endif
             @if(session('role')=='hr' || session('role')=='admin')

                @if('add-attendance'== Request::path() || 'upload-emp'== Request::path() || 'upload-sales'== Request::path() || 'file'==Request::path() || 'upload-tds'==Request::path() || 'upload-attendance'==Request::path() || 'upload-bonus'==Request::path() || 'upload-incentive'==Request::path() || 'upload-ex_gratia'==Request::path() || 'upload-arrear'==Request::path())
                    <li class="active ">
                @else
                    <li>
                 @endif
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">settings</i>
                            <span>Upload Reports</span>
                        </a>
                        <ul class="ml-menu">

                        @if('add-attendance'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="add-attendance">
                                    <i class="material-icons">layers</i>
                                    <span>Attendance</span>
                                </a>
                            </li>
                            
                        @if('upload-attendance'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-attendance">
                                    <i class="material-icons">layers</i>
                                    <span>Monthly Attendance</span>
                                </a>
                            </li>

                        @if('upload-emp'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-emp">
                                    <i class="material-icons">layers</i>
                                    <span>Employee</span>
                                </a>
                            </li>

                        @if('upload-sales'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-sales">
                                    <i class="material-icons">layers</i>
                                    <span>Sales</span>
                                </a>
                            </li>

                        @if('file'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="file">
                                    <i class="material-icons">layers</i>
                                    <span>File</span>
                                </a>
                            </li>

                        @if('upload-tds'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-tds">
                                    <i class="material-icons">layers</i>
                                    <span>TDS</span>
                                </a>
                            </li>

                        @if('upload-bonus'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-bonus">
                                    <i class="material-icons">layers</i>
                                    <span>Bonus</span>
                                </a>
                            </li>

                        @if('upload-incentive'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-incentive">
                                    <i class="material-icons">layers</i>
                                    <span>Incentive</span>
                                </a>
                            </li>

                        @if('upload-ex_gratia'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-ex_gratia">
                                    <i class="material-icons">layers</i>
                                    <span>Ex Gratia</span>
                                </a>
                            </li>

                        @if('upload-arrear'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="upload-arrear">
                                    <i class="material-icons">layers</i>
                                    <span>Arrear</span>
                                </a>
                            </li>

                        </ul>
                    </li>

                @endif

                @if('notification'== Request::path())
                    <li class="active">
                @else
                    <li>
                @endif
                    <!-- <li class="active"> -->
                        <a href="notification">
                            <i class="material-icons">notifications</i>
                            <span>Notification</span>
                        </a>
                    </li>

            @if(session('role')=='hr' || session('role')=='admin')

                 @if('history'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                    <!-- <li class="active"> -->
                        <a href="history">
                            <i class="material-icons">history</i>
                            <span>History</span>
                        </a>
                    </li>


               @if('department'== Request::path() || 'designation'== Request::path() || 'branch'== Request::path() || 'division'== Request::path() || 'section'== Request::path() || 'sub-section'== Request::path() || 'working_hour'== Request::path() || 'bank'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif                    
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">business</i>
                            <span>Company Master</span>
                        </a>
                        <ul class="ml-menu">

                        @if('branch'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="branch">
                                    <i class="material-icons">layers</i>
                                    <span>Branch</span>
                                </a>
                            </li>

                        @if('bank'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="bank">
                                    <i class="material-icons">layers</i>
                                    <span>Bank</span>
                                </a>
                            </li>

                        @if('department'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="department">
                                    <i class="material-icons">layers</i>
                                    <span>Department</span>
                                </a>
                            </li>

                        @if('designation'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="designation">
                                    <i class="material-icons">layers</i>
                                    <span>Designation</span>
                                </a>
                            </li>

                        @if('division'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="division">
                                    <i class="material-icons">layers</i>
                                    <span>Division</span>
                                </a>
                            </li>

                        @if('section'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="section">
                                    <i class="material-icons">layers</i>
                                    <span>Section</span>
                                </a>
                            </li>
                            
                        @if('sub-section'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="sub-section">
                                    <i class="material-icons">layers</i>
                                    <span>Sub Section</span>
                                </a>
                            </li>

                        @if('working_hour'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="working_hour">
                                    <i class="material-icons">layers</i>
                                    <span>Working Hour</span>
                                </a>
                            </li>

                        </ul>
                    </li>
                
                @if('add-employee'== Request::path() || 'employee-list'== Request::path() || 'emp-search'== Request::path() || 'advance'== Request::path() || 'emp-doc'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif               
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">group</i>
                            <span>Employee</span>
                        </a>
                        <ul class="ml-menu">
                        @if('add-employee'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="add-employee">
                                    <i class="material-icons">group_add</i>
                                    <span>Add Employee</span>
                                </a>
                            </li>
                        @if('employee-list'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif  <a href="employee-list">
                                    <i class="material-icons">person</i>
                                    <span>Employee List</span>
                                </a>
                            </li>
<!-- 
                            @if('emp-search'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif  <a href="emp-search">
                                    <i class="material-icons">person</i>
                                    <span>Employee Search</span>
                                </a>
                            </li> -->


                        @if('emp-doc'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif  <a href="emp-doc">
                                    <i class="material-icons">file_upload</i>
                                    <span>Upload Employee Doc</span>
                                </a>
                            </li>

                        @if('advance'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="advance">
                                    <i class="material-icons">layers</i>
                                    <span>Advance Master</span>
                                </a>
                            </li>
                        @if('transfer_master'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                            <a href="transfer_master">
                                <i class="material-icons">layers</i>
                                <span>Transfer Master</span>
                            </a>
                        </li>

                        </ul>
                    </li>
            @endif 

            @if(session('role')=='hr' || session('role')=='admin')

               @if('create-team'== Request::path() || 'target'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif                    
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">business</i>
                            <span>Team Management</span>
                        </a>
                        <ul class="ml-menu">


                        @if('create-team'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="create-team">
                                    <i class="material-icons">layers</i>
                                    <span>Team</span>
                                </a>
                            </li>

                     @if('target'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="target">
                                    <i class="material-icons">layers</i>
                                    <span>Target</span>
                                </a>
                            </li>
                      <!-- @if('asign-target'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="asign-target">
                                    <i class="material-icons">layers</i>
                                    <span>Asign Target</span>
                                </a>
                            </li>-->
                        </ul>
                    </li> 
                     @endif 

                
                @if('attendance-search'== Request::path() || 'attendance-report'== Request::path() || 'monthly_attendance_report'== Request::path() || 'attendance-search'== Request::path() || 'edit_attendance'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif                    
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">event</i>
                            <span>Attendance</span>
                        </a>
                        <ul class="ml-menu">

                        <!-- @if(session('role')=='hr' || session('role')=='admin')

                        @if('add-attendance'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="add-attendance">
                                    <i class="material-icons">layers</i>
                                    <span>Add Attendance</span>
                                </a>
                            </li>

                        @endif -->

                        @if('attendance-report'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="attendance-report">
                                    <i class="material-icons">layers</i>
                                    <span>Attendance Report</span>
                                </a>
                            </li>

                        @if(session('role')=='hr' || session('role')=='admin')
                            @if('attendance-search'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                    <a href="attendance-search">
                                        <i class="material-icons">layers</i>
                                        <span>Attendance Search</span>
                                    </a>
                                </li>

                            @if('monthly_attendance_report'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                    <a href="monthly_attendance_report">
                                        <i class="material-icons">layers</i>
                                        <span>Monthly Attendance Report</span>
                                    </a>
                                </li>

                            @if('edit_attendance'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                    <a href="edit_attendance">
                                        <i class="material-icons">layers</i>
                                        <span>Edit Attendance</span>
                                    </a>
                                </li>

                        @endif

                        </ul>
                    </li>


               <!--  @if(session('role')=='hr' || session('role')=='admin')
                     @if('upload-sales'== Request::path())
                        <li class="active ">
                    @else
                        <li>
                    @endif
                            <a href="upload-sales">
                                <i class="material-icons">add</i>
                                <span>Upload Sales</span>
                            </a>
                        </li>
                @endif -->
               
                @if('leave'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="leave">
                            <i class="material-icons">flight</i>
                            <span>Leave</span>
                        </a>
                    </li>                

				@if('holidays'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="holidays">
                            <i class="material-icons">flight</i>
                            <span>Holidays</span>
                        </a>
                    </li>                   

                 @if('create-payslip'== Request::path() || 'payslip-list'== Request::path())
                        <li class="active ">
                    @else
                        <li>
                    @endif
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">layers</i>
                            <span>Payroll</span>
                        </a>
                        <ul class="ml-menu">

                        @if(session('role')!='sales' && session('role')!='employee')

                            @if('create-payslip'== Request::path())
                                <li class="active ">
                            @else
                                <li>
                            @endif
                                <a href="create-payslip">
                                    <i class="material-icons">layers</i>
                                    <span>Create Payslip</span>
                                </a>
                            </li>

                        @endif

                        @if('payslip-list'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="payslip-list">
                                    <i class="material-icons">layers</i>
                                    <span>Payslip List</span>
                                </a>
                            </li>
                            
                            @if('payslip-list'== Request::path())
                            <li class="active ">
                        @else
                            <li>
                        @endif
                                <a href="deletepayslip">
                                    <i class="material-icons">layers</i>
                                    <span>Delete Payslip</span>
                                </a>
                            </li>
                        </ul>
                    </li>

            @if(session('role')=='hr' || session('role')=='admin')

                @if('report'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="report">
                            <i class="material-icons"> view_list</i>
                            <span>Reports</span>
                        </a>
                    </li>
            @endif

            @if(session('role')=='hr' || session('role')=='admin')

                @if('expense'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="expense">
                            <i class="material-icons"> view_list</i>
                            <span>Expense</span>
                        </a>
                    </li>

                @if('notice-board'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="notice-board">
                            <i class="material-icons">notifications</i>
                            <span>Notice Board</span>
                        </a>
                    </li>

            @endif

                @if('feedback'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="feedback">
                            <i class="material-icons">feedback</i>
                            <span>Feedback</span>
                        </a>
                    </li>

                @if('account'== Request::path())
                    <li class="active ">
                @else
                    <li>
                @endif
                        <a href="account">
                            <i class="material-icons">settings</i>
                            <span>Account</span>
                        </a>
                    </li>

                    <!-- <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">widgets</i>
                            <span>Widgets</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Cards</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="pages/widgets/cards/basic.html">Basic</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/cards/colored.html">Colored</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/cards/no-header.html">No Header</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Infobox</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="pages/widgets/infobox/infobox-1.html">Infobox-1</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/infobox/infobox-2.html">Infobox-2</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/infobox/infobox-3.html">Infobox-3</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/infobox/infobox-4.html">Infobox-4</a>
                                    </li>
                                    <li>
                                        <a href="pages/widgets/infobox/infobox-5.html">Infobox-5</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">swap_calls</i>
                            <span>User Interface (UI)</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/ui/alerts.html">Alerts</a>
                            </li>
                            <li>
                                <a href="pages/ui/animations.html">Animations</a>
                            </li>
                            <li>
                                <a href="pages/ui/badges.html">Badges</a>
                            </li>

                            <li>
                                <a href="pages/ui/breadcrumbs.html">Breadcrumbs</a>
                            </li>
                            <li>
                                <a href="pages/ui/buttons.html">Buttons</a>
                            </li>
                            <li>
                                <a href="pages/ui/collapse.html">Collapse</a>
                            </li>
                            <li>
                                <a href="pages/ui/colors.html">Colors</a>
                            </li>
                            <li>
                                <a href="pages/ui/dialogs.html">Dialogs</a>
                            </li>
                            <li>
                                <a href="pages/ui/icons.html">Icons</a>
                            </li>
                            <li>
                                <a href="pages/ui/labels.html">Labels</a>
                            </li>
                            <li>
                                <a href="pages/ui/list-group.html">List Group</a>
                            </li>
                            <li>
                                <a href="pages/ui/media-object.html">Media Object</a>
                            </li>
                            <li>
                                <a href="pages/ui/modals.html">Modals</a>
                            </li>
                            <li>
                                <a href="pages/ui/notifications.html">Notifications</a>
                            </li>
                            <li>
                                <a href="pages/ui/pagination.html">Pagination</a>
                            </li>
                            <li>
                                <a href="pages/ui/preloaders.html">Preloaders</a>
                            </li>
                            <li>
                                <a href="pages/ui/progressbars.html">Progress Bars</a>
                            </li>
                            <li>
                                <a href="pages/ui/range-sliders.html">Range Sliders</a>
                            </li>
                            <li>
                                <a href="pages/ui/sortable-nestable.html">Sortable & Nestable</a>
                            </li>
                            <li>
                                <a href="pages/ui/tabs.html">Tabs</a>
                            </li>
                            <li>
                                <a href="pages/ui/thumbnails.html">Thumbnails</a>
                            </li>
                            <li>
                                <a href="pages/ui/tooltips-popovers.html">Tooltips & Popovers</a>
                            </li>
                            <li>
                                <a href="pages/ui/waves.html">Waves</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">assignment</i>
                            <span>Forms</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/forms/basic-form-elements.html">Basic Form Elements</a>
                            </li>
                            <li>
                                <a href="pages/forms/advanced-form-elements.html">Advanced Form Elements</a>
                            </li>
                            <li>
                                <a href="pages/forms/form-examples.html">Form Examples</a>
                            </li>
                            <li>
                                <a href="pages/forms/form-validation.html">Form Validation</a>
                            </li>
                            <li>
                                <a href="pages/forms/form-wizard.html">Form Wizard</a>
                            </li>
                            <li>
                                <a href="pages/forms/editors.html">Editors</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">view_list</i>
                            <span>Tables</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/tables/normal-tables.html">Normal Tables</a>
                            </li>
                            <li>
                                <a href="pages/tables/jquery-datatable.html">Jquery Datatables</a>
                            </li>
                            <li>
                                <a href="pages/tables/editable-table.html">Editable Tables</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">perm_media</i>
                            <span>Medias</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/medias/image-gallery.html">Image Gallery</a>
                            </li>
                            <li>
                                <a href="pages/medias/carousel.html">Carousel</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">pie_chart</i>
                            <span>Charts</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/charts/morris.html">Morris</a>
                            </li>
                            <li>
                                <a href="pages/charts/flot.html">Flot</a>
                            </li>
                            <li>
                                <a href="pages/charts/chartjs.html">ChartJS</a>
                            </li>
                            <li>
                                <a href="pages/charts/sparkline.html">Sparkline</a>
                            </li>
                            <li>
                                <a href="pages/charts/jquery-knob.html">Jquery Knob</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">content_copy</i>
                            <span>Example Pages</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/examples/sign-in.html">Sign In</a>
                            </li>
                            <li>
                                <a href="pages/examples/sign-up.html">Sign Up</a>
                            </li>
                            <li>
                                <a href="pages/examples/forgot-password.html">Forgot Password</a>
                            </li>
                            <li>
                                <a href="pages/examples/blank.html">Blank Page</a>
                            </li>
                            <li>
                                <a href="pages/examples/404.html">404 - Not Found</a>
                            </li>
                            <li>
                                <a href="pages/examples/500.html">500 - Server Error</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">map</i>
                            <span>Maps</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/maps/google.html">Google Map</a>
                            </li>
                            <li>
                                <a href="pages/maps/yandex.html">YandexMap</a>
                            </li>
                            <li>
                                <a href="pages/maps/jvectormap.html">jVectorMap</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <i class="material-icons">trending_down</i>
                            <span>Multi Level Menu</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="javascript:void(0);">
                                    <span>Menu Item</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);">
                                    <span>Menu Item - 2</span>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" class="menu-toggle">
                                    <span>Level - 2</span>
                                </a>
                                <ul class="ml-menu">
                                    <li>
                                        <a href="javascript:void(0);">
                                            <span>Menu Item</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0);" class="menu-toggle">
                                            <span>Level - 3</span>
                                        </a>
                                        <ul class="ml-menu">
                                            <li>
                                                <a href="javascript:void(0);">
                                                    <span>Level - 4</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="pages/changelogs.html">
                            <i class="material-icons">update</i>
                            <span>Changelogs</span>
                        </a>
                    </li>
                    <li class="header">LABELS</li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-red">donut_large</i>
                            <span>Important</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-amber">donut_large</i>
                            <span>Warning</span>
                        </a>
                    </li>
                    <li>
                        <a href="javascript:void(0);">
                            <i class="material-icons col-light-blue">donut_large</i>
                            <span>Information</span>
                        </a>
                    </li> -->
                </ul>
            </div>
            <!-- #Menu -->
            <!-- Footer -->
            <div class="legal">
                <div class="copyright">
                    &copy; 2018 - 2019 <a href="javascript:void(0);">Shree Shivam</a>
                </div>
                <!-- <div class="version">
                    <b>Version: </b> 1.0.5
                </div> -->
            </div>
            <!-- #Footer -->
        </aside>
        <!-- #END# Left Sidebar -->
        <!-- Right Sidebar -->
        @include('inc.right-sidebar')
        <!-- #END# Right Sidebar -->
    </section>