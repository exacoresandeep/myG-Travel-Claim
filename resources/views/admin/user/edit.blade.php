<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>All users :: DMS</title>
    @include("admin.include.header")
</head>
<body>
    @include("admin.include.sidebar-menu")
    <div class="main-area">
        <div class="back-btn" id="back-button">
            <a href="{{ url('list_users') }}">
            <i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back
            </a>
        </div>
        <h2 class="main-heading">Edit User</h2>
        <div class="dash-all pt-0">
            <div class="dash-table-all" style="max-width:700px;">
                <form method="POST" action="{{ url('update_user_submit') }}">
                    <input type="hidden" name="id" value="{{$User->id}}">
                    @csrf

                    <table class="table table-striped">
                       
                        <tr>
                            <td>Employee ID<span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_id') is-invalid @enderror" name="emp_id" required autocomplete="off" value="{{$User->emp_id}}" readonly="">
                                @error('emp_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Employee Name<span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_name') is-invalid @enderror" name="emp_name" required autocomplete="off" value="{{$User->emp_name}}" readonly="">
                                @error('emp_name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>

                        
                        <tr>
                            <td>Email <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" required autocomplete="off" value="{{$User->email}}" readonly="">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>

                         <tr>
                            <td>Employee Phonenumber <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_phonenumber') is-invalid @enderror" name="emp_phonenumber" required autocomplete="off" value="{{$User->emp_phonenumber}}" readonly="">
                                @error('emp_phonenumber')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Department <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_department') is-invalid @enderror" name="emp_department" required autocomplete="off" value="{{$User->emp_department}}" readonly="">
                                @error('emp_department')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>


                        <tr>
                            <td>Employee Branch <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <select class="form-control" name="emp_branch">
                                    <option value="">Select</option>
                                    @foreach($branch as $val)
                                    <option value="{{$val->BranchID}}" {{ $User->emp_branch == $val->BranchID ? 'selected' : '' }}>{{$val->BranchName}}</option>
                                    @endforeach
                                </select>
                                
                            </td>
                        </tr>


                        <tr>
                            <td>Employee Base location <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_baselocation') is-invalid @enderror" name="emp_baselocation" required autocomplete="off" value="{{$User->emp_baselocation}}" readonly="">
                                @error('emp_baselocation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>

                        <tr>
                            <td>Employee Designation <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_designation') is-invalid @enderror" name="emp_designation" required autocomplete="off" value="{{$User->emp_designation}}" readonly="">
                                @error('emp_designation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Employee Grade <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_grade') is-invalid @enderror" name="emp_grade" required autocomplete="off" value="{{$User->emp_grade}}" readonly="">
                                @error('emp_grade')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>Reporting Person <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                
                                <select class="form-control" name="reporting_person">
                                    <option value="">Select</option>
                                    @foreach($userData as $val)
                                    <option value="{{$val->emp_id}}" {{ $User->reporting_person == $val->id ? 'selected' : '' }}>{{$val->emp_name}}</option>
                                    @endforeach
                                </select>
                                
                            </td>
                        </tr>
                        <tr>
                            <td>Employee Role <span style="color: red;">*</span></td>
                            <td width="10">:</td>
                            <td>
                                <input type="text" class="form-control @error('emp_role') is-invalid @enderror" name="emp_role" required autocomplete="off" value="{{$User->emp_role}}" readonly="">
                                @error('emp_role')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
