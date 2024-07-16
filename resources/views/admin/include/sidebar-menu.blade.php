<div class="menu-left">
    <h4 class="dash-title">
      <img src="images/dashboard-icon.svg">
      <a href="index.php">myG Dashboard</a>
    </h4>    
    <div id="accordion">
      <div class="card">
        <div class="card-header">
          <a class="card-link" data-toggle="collapse" href="#collapseOne">
            <i class="fa fa-files-o" aria-hidden="true"></i> Claim Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
          </a>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
          <div class="card-body">
            <a href="{{url('claim_request')}}">
              Requested Claims <i class="" aria-hidden="true"></i>
            </a>
            <a href="approved-claims.php">
              Approved Claims <i class="" aria-hidden="true"></i>
            </a>
            <a href="settled-claims.php">
              Settled Claims <i class="" aria-hidden="true"></i>
            </a>
            <a href="rejected-claims.php">
              Rejected Claims <i class="" aria-hidden="true"></i>
            </a>
            <a href="ro-approval-pending.php">
              R.O. Approval Pending <i class="" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">
        <a class="collapsed card-link" href="{{url('/branch')}}">
            <i class="fas fa-code-branch" aria-hidden="true"></i> Branch management <i class="" aria-hidden="true"></i>
          </a>
        </div>

        <div class="card-header">
        <a class="collapsed card-link" href="{{url('/grade')}}">
            <i class="fas fa-book-open" aria-hidden="true"></i> Grade management <i class="" aria-hidden="true"></i>
          </a>
        </div>
       
        </div>
      </div>

      <div class="card">
        <div class="card-header">
          <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
          <i class="fa fa-inr" aria-hidden="true"></i> Manage Advance Payment <i class="fa fa-caret-right second" aria-hidden="true"></i>
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
      <!--
      <div class="card">
        <div class="card-header">
          <a class="collapsed card-link" href="#">
            <i class="fa fa-th" aria-hidden="true"></i> Branch Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
          </a>
        </div>
      </div>
      -->
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


  <script>
// Function to toggle accordion and manage icon
function toggleAccordion(target, element) {
  var icon = element.querySelector('.second');
  var collapse = document.getElementById(target);
  
  if (collapse.classList.contains('show')) {
    collapse.classList.remove('show');
    element.setAttribute('aria-expanded', 'false');
    icon.classList.remove('fa-caret-down');
    icon.classList.add('fa-caret-right');
  } else {
    collapse.classList.add('show');
    element.setAttribute('aria-expanded', 'true');
    icon.classList.remove('fa-caret-right');
    icon.classList.add('fa-caret-down');
  }
}

// Add event listener to handle dropdown toggling
document.querySelectorAll('.card-link').forEach(function(cardLink) {
  cardLink.addEventListener('click', function() {
    var isCollapsed = !this.getAttribute('aria-expanded') || this.getAttribute('aria-expanded') === 'false';
    var icon = this.querySelector('.second');
    
    if (isCollapsed) {
      icon.classList.remove('fa-caret-right');
      icon.classList.add('fa-caret-down');
    } else {
      icon.classList.remove('fa-caret-down');
      icon.classList.add('fa-caret-right');
    }
  });
});
</script>