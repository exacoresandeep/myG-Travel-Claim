<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">

<title>Report management :: MyG</title>
@include("admin.include.header")

@include("admin.include.sidebar-menu")

  <div class="main-area">
    <h2 class="main-heading">Report management</h2>
    <div class="dash-all">
      <div class="dash-table-all">
        <div class="filter-block">
          <div class="row">
            <div class="col-md-3">
              <label>From</label>
              <input type="date" class="form-control" name="FromDate">
            </div>
            <div class="col-md-3">
              <label>To</label>
              <input type="date" class="form-control" name="ToDate">
            </div>
            <div class="col-md-3">
              <label>Status</label>
              <select class="form-control" name="Status">
                <option>Select</option>
                <option>Pending</option>
                <option>Approved</option>
                <option>Rejected</option>
                <option>Settled</option>
              </select>
            </div>
            
            <div class="col-md-3">
              <label>Type of trip</label>
              <select class="form-control" name="TripType">
                <option>Select</option>
              </select>
            </div>
            <div class="col-md-3">
              <label>Employee Code</label>
              <input type="text" class="form-control" name="">
            </div>
            <div class="col-md-3">
              <label>Grade</label>
              <select class="form-control">
                <option>Select</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>
            <div class="col-md-3">
              <label>Branch name</label>
              <input type="text" class="form-control" name="">
            </div>            
          </div>
          <a href="" class="btn btn-primary btn-search" id="report-managament-search-btn">Search</a>
            
        </div>      
        <div class="sort-block text-end">
         
        </div>
        <div class="col-lg-12 d-flex justify-content-end row mb-2">
          <a href="#" class="btn btn-success"><i class="fa fa-download" aria-hidden="true"></i> Export</a>
        </div>
        <table class="table table-striped approved-claim-table report-management-table" id="approved-management-table">
          <thead>
            <!-- <th><input type="checkbox" class="checkbox"></th> -->
            <th>Sl.</th>
            <th>Trip ID</th>
            <th>Date</th>
            <th>Type of trip</th>            
            <th>Employee name/ID</th>
            <th>Branch</th>
            <th>Grade</th>
            <th>Department</th>
            <!-- <th>Category</th> -->
            <th>Amount</th>
          </thead>
          <tbody>
          <tr>
          <td colspan="11" class="text-center">No Data</td>
          </tr>        

          </tbody>
        </table>
        <div class="pagination-block">
          <ul class="pagination pagination-sm justify-content-end">
            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">Next</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- The Modal -->
<div class="modal fade" id="myModal">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
    
      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title text-danger">Are you Sure, you want to delete the file?</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      
      <!-- Modal body -->
      <div class="modal-body">
        Once you delete the file, you will no longer be able to access the file. Click "Yes" to proceed or else click "Cancel".
      </div>
      
      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Yes</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Cancel</button>
      </div>
      
    </div>
  </div>
</div>

@include("admin.include.footer")
<body>
  </html>
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
          // $("#report-managament-search-btn").on("click",function(){
            
         
           var table = $('#approved-management-table').DataTable({
               processing: true,
               serverSide: true,
               ajax: "{{ route('report_management_list') }}",
               order: ['1', 'DESC'],
               pageLength: 10,
               columns: [
                   { 
                       data: 'id', 
                       name: 'id', 
                       render: function(data, type, row, meta) {
                           return meta.row + 1; // meta.row is zero-based index
                       },
                       className: 'text-center'
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
                   { data: 'triptype', name: 'triptype' },
                   { data: 'UserData', name: 'UserData' },
                   { data: 'Branch', name: 'Branch' },
                   { data: 'Grade', name: 'Grade' },
                   { data: 'Department', name: 'Department' },
                   { data: 'TotalAmount', name: 'TotalAmount', className: 'text-right'},
                  //  { data: 'action', name: 'action', orderable: false, searchable: false, 
                  //     render: function(data, type, row) {
                  //       var viewUrl = '/approved_claims_view/' + row.TripClaimID;
                  //       return '<a href="'+viewUrl+'" class="btn btn-primary"><i class="fa fa-eye" aria-hidden="true"></i> View</a> <a href="javascript:void(0);" onclick="openCompleteModal(\'' + row.TripClaimID + '\')" class="btn btn-success"><i class="fa fa-check-square" aria-hidden="true"></i> Mark as Complete</a>';
                  //     }
                  //   }
               ]
           });
          // });

          
       });

      

       </script>