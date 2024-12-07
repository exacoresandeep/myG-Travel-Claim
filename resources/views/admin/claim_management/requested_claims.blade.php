<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>All Claim Requests :: MyG</title>
@include("admin.include.header")

@include("admin.include.sidebar-menu")
<div class="main-area">
    <h2 class="main-heading">All Requested Claims</h2>
    <div class="dash-all">
      <div class="dash-table-all">        
        <div class="sort-block">
          <!-- <div class="show-num">
            <span>Show</span>
            <select class="select">
              <option>20</option>
              <option>50</option>
              <option>100</option>
            </select>
            <span>Entries</span>
          </div>  
          <a href="" class="btn btn-primary">Delete</a>
          <div class="sort-by ml-auto">
            <select class="select">
              <option>Select</option>
              <option>Sort by latest</option>
              <option>Sort by oldest</option>
            </select>
          </div>-->
        </div>
        <table class="table table-striped request-claim-table" id="request-claim-table">
          <thead>            
            <th>Sl.</th>
            <th>Trip ID</th>
            <th>Date</th>
            <th>Employee name/ID</th>
            <th>Branch name/code</th>
            <th>Type of trip</th>            
            <th>Total amount</th>
            <th>Status</th>
            <th>Action</th>
          </thead>
          <tbody>
           
          </tbody>
        </table>
        <!-- <div class="pagination-block">
          <ul class="pagination pagination-sm justify-content-end">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
          </ul>
        </div> -->
      </div>
    </div>
  </>
  
</div>

<!-- approve confirmation Modal start -->
<div class="modal fade" id="markCompleteModal">
  <div class="modal-dialog modal-md modal-dialog-centered">
    <div class="modal-content">
      <!-- Modal body -->
      <div class="modal-body">
        <div class="mark-complete-sec">
          <img src="images/complete-check.svg" class="img-fluid">
          <h6>Are you sure?</h6>
          <p>You won’t be able to revert this  </p>
        </div>
      </div>
      <input type="hidden" value="" name="" id="modalTripClaimID">   
      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">   
          
        <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" data-dismiss="modal" id="confirmComplete">Complete</button>
      </div>
      
    </div>
  </div>
</div>

    <script>
      
      $(document).ready(function(){
           @if(session()->has('message'))
           swal({
               title: "Success!",
               text: "{{ session()->get('message') }}",
               icon: "success",
           });
           @endif

           // DataTables script
          
           var table = $('#request-claim-table').DataTable({
               processing: true,
               serverSide: true,
               ajax: "{{ route('requested_claims_list') }}",
               order: ['1', 'DESC'],
               pageLength: 10,
               columns: [
                   { 
                       data: 'id', 
                       name: 'id', 
                       render: function(data, type, row, meta) {
                           return meta.row + 1; // meta.row is zero-based index
                       }
                   },
                   { 
                        data: 'TripClaimID', 
                        name: 'TripClaimID', 
                        render: function(data, type, row) {
                            // Modify the TripClaimID to add 'TMG' and remove the first 8 characters
                            return 'TMG' + data.substring(8);
                        }
                    },
                   { data: 'created_at', name: 'created_at' },
                   { data: 'UserData', name: 'UserData' },
                   { data: 'VisitBranchID', name: 'VisitBranchID' },
                   { data: 'TripTypeID', name: 'TripTypeID' },
                   { data: 'TotalAmount', name: 'TotalAmount', className: 'text-right'},
                   { data: 'Status', name: 'Status'},
                   { data: 'action', name: 'action', orderable: false, searchable: false}
                     
               ]
           });


           $('#confirmComplete').click(function() {
                var TripClaimID = $('#modalTripClaimID').val();
                
                // Perform the AJAX request to mark the claim as complete
                $.ajax({
                    url: "{{ route('complete_approved_claim') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        TripClaimID: TripClaimID
                    },
                    success: function(response) {
                        if(response.success) {
                            // Close the modal
                            $('#markCompleteModal').modal('hide');
                            
                            // Show SweetAlert success message
                            Swal.fire({
                                title: "Success!",
                                text: "Claim marked as complete.",
                                icon: "success",
                            }).then(function() {
                                // Reload DataTable after SweetAlert confirmation
                                $('#approved-claim-table').DataTable().ajax.reload();
                            });
                        } else {
                            // Show SweetAlert error message
                            Swal.fire({
                                title: "Error!",
                                text: "An error occurred while marking the claim as complete.",
                                icon: "error",
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        // Show SweetAlert error message for AJAX error
                        Swal.fire({
                            title: "Error!",
                            text: "Failed to mark claim as complete. Please try again later.",
                            icon: "error",
                        });
                    }
                });
            });
       });

      function openCompleteModal(TripClaimID) {
          $('#modalTripClaimID').val(TripClaimID);
          $('#markCompleteModal').modal('show');
      }

      

   
      
       </script>
 
@include("admin.include.footer")
<body>
  </html>