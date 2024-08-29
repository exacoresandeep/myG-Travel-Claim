<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Policy </title>
    @include("admin.include.header")
  </head>
  <body>
    @include("admin.include.sidebar-menu")

    <div class="main-area">
      <h2 class="main-heading">Edit Policy</h2>  
      <div class="dash-all pt-0">
        <div class="dash-table-all" style="max-width:700px;">  
          <form method="POST" action="{{ url('update_policy_management_submit') }}">
            @csrf   
            <table class="table table-striped">        
             
            <tr>
              <td>Grade<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                <input type="hidden" value="{{$data->PolicyID}}" id="PolicyID" name="PolicyID">
                  <select class="form-control @error('GradeID') is-invalid @enderror" name="GradeID" required>
                      <option value="">Select Grade</option>
                      @foreach($grades as $grade)
                          <option value="{{ $grade->GradeID }}" {{ $data->GradeID == $grade->GradeID ? 'selected' : '' }}>
                              {{ $grade->GradeName }}
                          </option>
                      @endforeach
                  </select>
                  @error('GradeID')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </td>
            </tr>
            <tr>
              <td>Category<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                  <select class="form-control @error('CategoryID') is-invalid @enderror" name="CategoryID" id="CategoryID" required>
                      <option value="">Select Category</option>
                      @foreach($categories as $category)
                        <option value="{{ $category->CategoryID }}" {{ $data->subCategoryDetails->CategoryID == $category->CategoryID ? 'selected' : '' }}>
                            {{ $category->CategoryName }}
                        </option>
                      @endforeach
                  </select>
                  @error('CategoryID')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </td>
            </tr>
            <tr>
              <td>SubCategory<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                  <select class="form-control @error('SubCategoryID') is-invalid @enderror" name="SubCategoryID" id="SubCategoryID" required>
                      <option value="">Select SubCategory</option>
                      @foreach($subCategories as $subCategory)
                          <option value="{{ $subCategory->SubCategoryID }}" {{ $data->SubCategoryID == $subCategory->SubCategoryID ? 'selected' : '' }}>
                              {{ $subCategory->SubCategoryName }}
                          </option>
                      @endforeach
                  </select>
                  @error('SubCategoryID')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </td>
            </tr>
            <tr>
              <td>Policy Type<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                  <select class="form-control @error('GradeType') is-invalid @enderror" name="GradeType" id="GradeType" required>
                    <option value="">Select Type</option>
                    <option value="Class" {{ $data->GradeType == "Class" ? 'selected' : '' }}>Class</option>
                    <option value="Amount" {{ $data->GradeType == "Amount" ? 'selected' : '' }}>Amount</option>
                  </select>
                  @error('GradeType')
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                      </span>
                  @enderror
              </td>
            </tr> 
            <tr id="ClassRow">
              <td>Class<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                  <input type="text" class="form-control @error('GradeClass') is-invalid @enderror" name="GradeClass" id="GradeClass" value=" {{$data->GradeClass}}">
                  @error('GradeClass')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </td>
            </tr>
            <tr id="AmountRow">
              <td>Amount<span style="color: red;">*</span></td>
              <td width="10">:</td>
              <td>
                  <input type="number" class="form-control @error('GradeAmount') is-invalid @enderror" name="GradeAmount" id="GradeAmount" value="{{$data->GradeAmount}}">
                  @error('GradeAmount')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                  @enderror
              </td>
            </tr>      
          </table>
          <button class="button_orange" type="Submit">Submit</button>
        </form>
      </div>
    </div>
  </div>

    @include("admin.include.footer")
  </body>
</html>
<script>
  $(document).ready(function(){

    toggleRows($('#GradeType').val());

    // Event listener for GradeType selection change
    $('#GradeType').change(function() {
        toggleRows($(this).val());
    });
    $.ajax({
          url: "{{ route('get-subcategories') }}",
          type: "POST",
          data: {
              _token: "{{ csrf_token() }}",
              category_id: $("#CategoryID").val()
          },
          success: function (data) {
            $('#SubCategoryID').empty();
            $('#SubCategoryID').append('<option value="">Select</option>');
            $.each(data, function (key, value) {
              var selected = (value.SubCategoryID == {{ $data->SubCategoryID }}) ? 'selected' : '';
              $('#SubCategoryID').append('<option value="' + value.SubCategoryID + '" ' + selected + '>' + value.SubCategoryName + '</option>');
          });
          }
        });
    function toggleRows(gradeType) {
        if (gradeType == "Class") {
            $('#ClassRow').show();
            $('#AmountRow').hide();
        } else if (gradeType == "Amount") {
            $('#ClassRow').hide();
            $('#AmountRow').show();
        } else {
            // Hide both rows if no valid selection
            $('#ClassRow').hide();
            $('#AmountRow').hide();
        }
    }

    $("#CategoryID").on("change",function(){
      var category_id = $(this).val();
      if (category_id) {
        $.ajax({
          url: "{{ route('get-subcategories') }}",
          type: "POST",
          data: {
              _token: "{{ csrf_token() }}",
              category_id: category_id
          },
          success: function (data) {
            $('#SubCategoryID').empty();
            $('#SubCategoryID').append('<option value="">Select</option>');
            $.each(data, function (key, value) {
                $('#SubCategoryID').append('<option value="' + value.SubCategoryID + '">' + value.SubCategoryName + '</option>');
            });
          }
        });
      } else {
        $('#SubCategoryID').empty();
        $('#SubCategoryID').append('<option value="">Select</option>');
      }
    });
    $('#GradeType').on('change', function() {
      var gradeType = $(this).val();
      if (gradeType === 'Class') {
        $('#ClassRow').show();
        $('#AmountRow').hide();
      } else if (gradeType === 'Amount') {
        $('#ClassRow').hide();
        $('#AmountRow').show();
      } else {
        $('#ClassRow').hide();
        $('#AmountRow').hide();
      }
    });

  });
</script>