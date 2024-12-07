<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Category Preview :: MyG</title>
  
  <!-- Include header -->
  @include("admin.include.header")
  
</head>
<body>
  <!-- Include sidebar menu -->
  @include("admin.include.sidebar-menu")
  
  <div class="main-area">    
    <div class="claim-cover">
      <div class="back-btn" id="back-button">
        <a href="#">
          <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back
        </a>
      </div>
      <div class="bg-cover">
        <table class="table">
        <tr>
              <th width="150">Grade</th>
              <td width="10">:</td>
              <td>
                  {{ $data->viewgradeDetails->GradeName }}
                  
              </td>
            </tr>
            <tr>
              <th width="150">Category</th>
              <td width="10">:</td>
              <td>
              {{ $data->subCategoryDetails->category->CategoryName }}
              </td>
            </tr>
            <tr>
              <th width="150">SubCategory</th>
              <td width="10">:</td>
              <td>
              {{ $data->subCategoryDetails->SubCategoryName }}
              </td>
            </tr>
            <tr>
              <th width="150">Policy Type</th>
              <td width="10">:</td>
              <td>
              {{ $data->GradeType }}
              </td>
            </tr> 
            <tr id="ClassRow" width="150">
                <th>Class</th>
                <td width="10">:</td>
                <td>
                    {{ $data->GradeClass ?? 'N/A' }}
                </td>
            </tr>
            <tr id="AmountRow" width="150">
                <th>Amount</th>
                <td width="10">:</td>
                <td>
                    {{ $data->GradeAmount ?? 'N/A' }}
                </td>
            </tr>
         
          <tr>
            <th width="150">Status</th>
            <td>:</td>
            <td>
            <?php if($data->Status == 0)
            {
              echo "Inactive";
            }
            elseif($data->Status == 1)
            {
              echo "Active";
            }
            else
            {
              echo "Deleted";
            }
            ?></td>
          </tr>
        </table>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    document.getElementById('back-button').addEventListener('click', function(event) {
      event.preventDefault();
      window.history.back();
    });
  </script>
  
  <!-- Include footer -->
  @include("admin.include.footer")
  
</body>
</html>
