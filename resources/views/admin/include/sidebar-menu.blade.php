 
  <div class="menu-left">
        <h4 class="dash-title">
            <img src="{{ asset ('images/dashboard-icon.svg') }}">
            
            <a href="index.php">myG Dashboard</a>
        </h4> 
        <div class="menu-container">
          
        
          <div id="accordion">
              <div class="card">
                  <div class="card-header">
                      <a class="card-link" data-toggle="collapse" href="#collapseOne">
                          <i class="fa fa-files-o" aria-hidden="true"></i> Claim Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
                      </a>
                  </div>
                  <div id="collapseOne" class="collapse" data-parent="#accordion">
                      <div class="card-body">
                          <a href="{{url('claim_request')}}">Requested Claims</a>
                          <a href="{{url('approved_claims')}}">Approved Claims</a>
                          <a href="{{url('settled_claims')}}">Settled Claims</a>
                          <a href="{{url('rejected_claims')}}">Rejected Claims</a>
                          <a href="{{url('ro_approval_pending')}}">R.O. Approval Pending</a>
                      </div>
                  </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="{{url('/branch')}}">
                  <i class="fa fa-building-o" aria-hidden="true"></i> Branch management <i class="" aria-hidden="true"></i>
                    
                  </a>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="{{url('/grade')}}">
                    <i class="fa fa-graduation-cap" aria-hidden="true"></i> Grade management <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
                  <i class="fa fa-money" aria-hidden="true"></i> Manage Advance Payment <i class="fa fa-caret-right second" aria-hidden="true"></i>
                </a>
                </div>
                <div id="collapseTwo" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <a href="search.php">Normal Search <i class="" aria-hidden="true"></i></a>
                    <a href="advanced-search.php">Advanced Search <i class="" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>

              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="{{url('list_users')}}">
                    <i class="fa fa-users" aria-hidden="true"></i> User Management <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="role-management.php">
                    <i class="fa fa-user" aria-hidden="true"></i> Role Management <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="report-management.php">
                    <i class="fa fa-file-text" aria-hidden="true"></i> Report Management <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
            

              <div class="card">
                <div class="card-header">
                  <a class="card-link" data-toggle="collapse" href="#collapseFive">
                    <i class="fa fa-list-alt" aria-hidden="true"></i> Category Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
                  </a>
                </div>
                <div id="collapseFive" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                  <a class="collapsed card-link" href="{{url('claim_category')}}">
                    <i class="" aria-hidden="true"></i> Category Claim  <i class="" aria-hidden="true"></i>
                  </a>
                    <a class="collapsed card-link" href="{{url('sub_claim_category')}}">
                    <i class="" aria-hidden="true"></i> Sub Category Claim  <i class="" aria-hidden="true"></i>
                  </a>
                  </div>
                </div>
              </div>



              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" data-toggle="collapse" href="#collapseFour">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i> CMD Approvals <i class="fa fa-caret-right second" aria-hidden="true"></i>
                  </a>
                </div>
                <div id="collapseFour" class="collapse" data-parent="#accordion">
                  <div class="card-body">
                    <a href="cmd-approval-list.php">Approval requests <i class="fa fa-caret-right" aria-hidden="true"></i></a>
                    <a href="add-user.php">Add users <i class="fa fa-caret-right" aria-hidden="true"></i></a>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="{{url('/trip_type_mgmt')}}">
                    <i class="fa fa-road" aria-hidden="true"></i> Type of Trip Management <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
              <div class="card">
                <div class="card-header">
                  <a class="collapsed card-link" href="profile.php">
                    <i class="fa fa-user-circle-o" aria-hidden="true"></i> Profile <i class="" aria-hidden="true"></i>
                  </a>
                </div>
              </div>
          </div>
        
        </div>
</div>
