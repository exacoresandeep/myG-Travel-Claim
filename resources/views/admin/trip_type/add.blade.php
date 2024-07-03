<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Type of Trip management :: DMS</title>
    @include("admin.include.header")
  </head>
  <body>
    @include("admin.include.sidebar-menu")

    <div class="main-area">
      <h2 class="main-heading">Add Type of Trip management</h2>  
      <div class="dash-all pt-0">
        <div class="dash-table-all" style="max-width:700px;">  
          <form method="POST" action="{{ url('add_triptype_submit') }}">
            @csrf   
            <table class="table table-striped">        
              <tr>
                <td>Trip Type Name<span style="color: red;">*</span></td>
                <td width="10">:</td>
                <td>
                  <input type="text" class="form-control @error('triptype') is-invalid @enderror" name="triptype" value="{{ old('triptype') }}" required autocomplete="off">
                  @error('triptype')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror  
                </td>
              </tr>
            </table>
            <button class="button_orange">Submit</button>
          </form>
        </div>
      </div>
    </div>

    @include("admin.include.footer")
  </body>
</html>
