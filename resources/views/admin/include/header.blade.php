
<!-- Custom CSS for DataTables pagination -->
<style type="text/css">
  /* Custom styles for previous and next buttons */
  .dataTables_wrapper .dataTables_paginate .paginate_button.previous,
  .dataTables_wrapper .dataTables_paginate .paginate_button.next {
    background-color: #E95815 !important; /* Orange background color */
    color: white !important; /* White text color */
    border-color: #E95815 !important; /* Border color */
    padding: 5px 10px; /* Adjust padding as needed */
    border-radius: 5px; /* Optional: Round corners */
  }

  /* Hover state */
  .dataTables_wrapper .dataTables_paginate .paginate_button.previous:hover,
  .dataTables_wrapper .dataTables_paginate .paginate_button.next:hover {
    background-color: #FFA500 !important; /* Lighter orange background on hover */
    border-color: #FFA500 !important; /* Lighter orange border color on hover */
  }
</style>
<link rel="icon" href="favicon.ico">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

<!-- Bootstrap core CSS -->
<link href="{{ asset ('css/bootstrap.min.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset ('font-awesome/css/font-awesome.min.css') }}">
<link href="{{ asset ('css/jquery.fancybox.css') }}" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="{{ asset ('css/style.css') }}" rel="stylesheet">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"/>
<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>  
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script src = "http://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.0/sweetalert.min.js"></script>

<style type="text/css">
  .button_orange {
    background-color: #E95815;
    color: white;
    padding: 10px 10px;
    text-align: center;
    text-decoration: none;
    display: inline-block;
    font-size: 16px;
    margin: 4px 2px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    transition-duration: 0.4s;
}

.button_orange:hover {
    background-color: #E95815;
    color: white;
}
</style>
</head>

<body>
<nav class="navbar navbar-expand-md navbar-light fixed-top bg-light">
  <a class="navbar-brand" href="index.php">
    <img src="{{ asset ('images/myg-logo.png') }}">
  </a>
  <!--
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  -->
  <div class="date mr-auto">
    <?php date_default_timezone_set('Asia/Kolkata');
     echo date('d-m-Y')?>,<?php echo date('l')?> | <?php echo date('h:i A')?>
  </div>
  <div class="account-sec ml-auto">
    <a href="notifications.php" class="noti-sec">
      <i class="fa fa-bell" aria-hidden="true"></i>
      <span>10</span>
    </a>
    <span class="dropdown">      
      <a class="dropdown-toggle" data-toggle="dropdown">
        <img src="images/myg-logo.png"> <span>{{ Auth::user()->emp_name }}</span>
      </a>
      

      <div class="dropdown">
   
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        <a class="dropdown-item" href="{{ url('view_user/'.Auth::user()->id) }}">User Profile</a>
        <a class="dropdown-item" href="settings.php">Settings</a>
        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
    </div>
</div>

    </span>
  </div>
</nav>