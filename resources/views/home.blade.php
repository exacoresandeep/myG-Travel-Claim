<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>MyG :: Claim Management Applicationww</title>
@include("admin.include.header")

<!-- <div class="main-content"> -->
@include("admin.include.sidebar-menu")
  <div class="main-area">
    <h2 class="main-heading">Hello, Welcome to MyG Travel Claim Dashboard</h2>
    
    <div class="dashbox-cover">
      <div class="dashbox-in">
        <div class="dashbox"> 
          <i class="fa fa-files-o" aria-hidden="true"></i>
          <h4>{{ $totalClaims }}</h4>
        </div>
        <h3>Claim requests</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <i class="fa fa-minus-square" aria-hidden="true"></i>
          <h4>{{ $pendingCount }}</h4>
        </div>
        <h3>Claim pending</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <i class="fa fa-check-square" aria-hidden="true"></i>
          <h4>{{ $settledCount }}</h4>
        </div>
        <h3>Claim settled</h3>
      </div>
      <div class="dashbox-in">
        <div class="dashbox"> 
          <i class="fa fa-chain-broken" aria-hidden="true"></i>
          <h4>{{ $approvedCount }}</h4>
        </div>
        <h3>Approved</h3>
      </div>      
    </div>
    <div class="dash-other">
      
    </div>
  </div>
<!-- </div> -->

@include("admin.include.footer")
