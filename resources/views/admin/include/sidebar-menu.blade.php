<div class="menu-left">
    <h4 class="dash-title">
        <img src="{{ asset('images/dashboard-icon.svg') }}">
        <a href="{{ url('/') }}">myG Dashboard</a>
    </h4> 
    <div class="menu-container">
        <div id="accordion">
            <div class="card">
                <div class="card-header">
                    <a class="card-link {{ Request::is('claim_request') || Request::is('approved_claims') || Request::is('settled_claims') || Request::is('rejected_claims') || Request::is('ro_approval_pending') ? 'active' : '' }}" data-toggle="collapse" href="#collapseOne">
                        <i class="fa fa-files-o" aria-hidden="true"></i> Claim Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="collapseOne" class="collapse {{ Request::is('claim_request') || Request::is('approved_claims') || Request::is('settled_claims') || Request::is('rejected_claims') || Request::is('ro_approval_pending') ? 'show' : '' }}" data-parent="#accordion">
                    <div class="card-body">
                        <a href="{{ url('claim_request') }}" class="{{ Request::is('claim_request') ? 'active' : '' }}">Requested Claims</a>
                        <a href="{{ url('approved_claims') }}" class="{{ Request::is('approved_claims') ? 'active' : '' }}">Approved Claims</a>
                        <a href="{{ url('settled_claims') }}" class="{{ Request::is('settled_claims') ? 'active' : '' }}">Settled Claims</a>
                        <a href="{{ url('rejected_claims') }}" class="{{ Request::is('rejected_claims') ? 'active' : '' }}">Rejected Claims</a>
                        <a href="{{ url('ro_approval_pending') }}" class="{{ Request::is('ro_approval_pending') ? 'active' : '' }}">R.O. Approval Pending</a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('branch') ? 'active' : '' }}" href="{{ url('/branch') }}">
                        <i class="fa fa-building-o" aria-hidden="true"></i> Branch management
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('grade') ? 'active' : '' }}" href="{{ url('/grade') }}">
                        <i class="fa fa-graduation-cap" aria-hidden="true"></i> Grade management
                    </a>
                </div>
            </div>

            <!-- <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('search') || Request::is('advanced-search') ? 'active' : '' }}" data-toggle="collapse" href="#collapseTwo">
                        <i class="fa fa-money" aria-hidden="true"></i> Manage Advance Payment <i class="fa fa-caret-right second" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="collapseTwo" class="collapse {{ Request::is('search') || Request::is('advanced-search') ? 'show' : '' }}" data-parent="#accordion">
                    <div class="card-body">
                        <a href="search.php" class="{{ Request::is('search') ? 'active' : '' }}">Normal Search</a>
                        <a href="advanced-search.php" class="{{ Request::is('advanced-search') ? 'active' : '' }}">Advanced Search</a>
                    </div>
                </div>
            </div> -->

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('list_users') ? 'active' : '' }}" href="{{ url('list_users') }}">
                        <i class="fa fa-users" aria-hidden="true"></i> User Management
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('role-management') ? 'active' : '' }}" href="{{ url('role-management') }}">
                        <i class="fa fa-user" aria-hidden="true"></i> Role Management
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('report-management') ? 'active' : '' }}" href="{{ url('report_management') }}">
                        <i class="fa fa-file-text" aria-hidden="true"></i> Report Management
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <a class="card-link {{ Request::is('claim_category') || Request::is('sub_claim_category') || Request::is('policy_management')? 'active' : '' }}" data-toggle="collapse" href="#collapseFive">
                        <i class="fa fa-list-alt" aria-hidden="true"></i> Policy Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="collapseFive" class="collapse {{ Request::is('claim_category') || Request::is('sub_claim_category') || Request::is('policy_management') ? 'show' : '' }}" data-parent="#accordion">
                    <div class="card-body">
                        <a href="{{ url('claim_category') }}" class="{{ Request::is('claim_category') ? 'active' : '' }}">Category</a>
                        <a href="{{ url('sub_claim_category') }}" class="{{ Request::is('sub_claim_category') ? 'active' : '' }}">Sub Category</a>
                        <a href="{{ url('policy_management') }}" class="{{ Request::is('policy_management') ? 'active' : '' }}">Grade - Category Policy</a>
                    </div>
                </div>
            </div>

            <!-- <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('cmd-approval-list.php') ? 'active' : '' }}" data-toggle="collapse" href="#collapseFour">
                        <i class="fa fa-check-circle-o" aria-hidden="true"></i> CMD Approvals <i class="fa fa-caret-right second" aria-hidden="true"></i>
                    </a>
                </div>
                <div id="collapseFour" class="collapse {{ Request::is('cmd-approval-list.php') ? 'show' : '' }}" data-parent="#accordion">
                    <div class="card-body">
                        <a href="cmd-approval-list.php" class="{{ Request::is('cmd-approval-list.php') ? 'active' : '' }}">Approval requests</a>
                        <a href="add-user.php" class="{{ Request::is('add-user.php') ? 'active' : '' }}">Add users</a>
                    </div>
                </div>
            </div> -->

            <div class="card">
                <div class="card-header">
                    <a class="collapsed card-link {{ Request::is('trip_type_mgmt') ? 'active' : '' }}" href="{{ url('/trip_type_mgmt') }}">
                        <i class="fa fa-road" aria-hidden="true"></i> Type of Trip Management
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                <a class="collapsed card-link {{ Request::is('view_user/' . Auth::user()->id) ? 'active' : '' }}" href="{{ url('view_user/' . Auth::user()->id) }}">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> Profile
                </a>
                </div>
            </div>
        </div>
    </div>
</div>
