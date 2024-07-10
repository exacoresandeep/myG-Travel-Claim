<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>All Branchs :: DMS</title>
    @include("admin.include.header")
</head>
<body>
    @include("admin.include.sidebar-menu")
    <div class="main-area">
        <h2 class="main-heading">Edit Branch</h2>
        <div class="dash-all pt-0">
            <div class="dash-table-all" style="max-width:700px;">
                <form method="POST" action="{{ url('update_branch_submit') }}">
                    <input type="hidden" name="id" value="{{$branch->BranchID}}">
                    @csrf

                    <table class="table table-striped">
                        <tr>
                            <td>Branch Name<span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('branch_name') is-invalid @enderror" name="branch_name" required autocomplete="off" value="{{$branch->BranchName}}">
                                @error('branch_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Branch Code<span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('branch_code') is-invalid @enderror" name="branch_code" required autocomplete="off" value="{{$branch->BranchCode}}">
                                @error('branch_code')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" name="Status" id="Status">
                                    <option value="">Select</option>
                                    <option value="1" {{ $branch->Status == "1" ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ $branch->Status == "0" ? 'selected' : '' }}>InActive</option>
                                    <option value="2" {{ $branch->Status == "2" ? 'selected' : '' }}>Deleted</option>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <button class="button_orange">Update</button>
                </form>
            </div>
        </div>
    </div>

    @include("admin.include.footer")
</body>
</html>
