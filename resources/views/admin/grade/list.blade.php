<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    
    <title>Grade :: MyG</title>
    
    <!-- Include CSS/JS or other head content -->
    @include("admin.include.header")
</head>
<body>
    <!-- Include sidebar/menu -->
    @include("admin.include.sidebar-menu")
    
    <div class="main-area">
        <h2 class="main-heading">All Grades</h2>
        <div class="dash-all">
            <div class="dash-table-all">        
                <div class="sort-block">
                    <a href="{{url('add_grade')}}" class="button_orange">Add Grade</a>
                </div>
                <table class="table table-striped grade-datatable" id="grade-datatable">
                    <thead>
                        <tr>
                            <th width="10%">
                                <input type="checkbox" id="select-all">&nbsp;&nbsp;&nbsp;
                                <button class="button_orange fa fa-trash" id="delete-selected"></button>
                            </th>
                            <th width="100 px">Sl.</th>
                            <th width="">Grade Name</th>
                            <th width="120 px">Status</th>
                            <th width="180 px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be populated dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include JavaScript -->
    <script type="text/javascript">
        @if(session()->has('message'))
        swal({
            title: "Success!",
            text: "{{ session()->get('message') }}",
            icon: "success",
        });
        @endif
        
        $('#select-all').on('change', function() {
            $('input[name="item_checkbox[]"]').prop('checked', $(this).prop('checked'));
        });
        
        $(function () {
            var table = $('.grade-datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get_grade_list') }}",
                order: ['1', 'DESC'],
                pageLength: 10,
                columns: [
                    { 
                        data: 'checkbox', 
                        name: 'checkbox', 
                        orderable: false, 
                        searchable: false,
                        render: function(data, type, row) {
                            return '<input type="checkbox" name="item_checkbox[]" value="' + row.GradeID + '">';
                        }
                    },
                    { 
                        data: 'id', 
                        name: 'id', 
                        render: function (data, type, row, meta) {
                            return meta.row + 1; // meta.row is zero-based index
                        }
                    },
                    { data: 'GradeName', name: 'GradeName' },
                    { 
                        data: 'Status', 
                        name: 'Status', 
                        render: function(data, type, row) {
                            if (data == 0) {
                                return '<span class="badge badge-success">Inactive</span>';
                            } else if (data == 1) {
                                return '<span class="badge badge-primary">Active</span>';
                            } else {
                                return '<span class="badge badge-danger">Deleted</span>';
                            }
                        }
                    },
                    { data: 'action', name: 'action', orderable: false, searchable: false }
                ]
            });
        });

        function delete_grade_modal(id) {
            var id = id; 
            swal({
                title: 'Are you sure?',
                text: "Are you sure you want to delete this grade?",
                icon: 'warning',
                buttons: true,
                dangerMode: true
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        type:'GET',
                        url:'{{url("/delete_grade")}}/' + id,
                        data: {
                            "_token": "{{ csrf_token() }}",
                        },
                        success:function(data) {
                            swal({
                                title: "Success!",
                                text: "Grade has been deleted!..",
                                icon: "success",
                            });
                            setTimeout(function() {
                                window.location.href = "{{url("grade")}}";
                            }, 2000);
                        }
                    });
                }
            }).then((willCancel) => {
                if (willCancel) {
                    window.location.href = "{{url("grade")}}";
                }
            }); 
        }

        $('#delete-selected').on('click', function() {
            var ids = $('input[name="item_checkbox[]"]:checked').map(function() {
                return $(this).val();
            }).get().filter(function(value) {
                return value !== undefined && value !== '';
            });

            if (ids.length > 0) {
                swal({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete this grade?",
                    icon: 'warning',
                    buttons: true,
                    dangerMode: true
                }).then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            url: '{{url("/delete_multi_grade")}}',
                            method: 'POST',
                            data: { 
                                ids: ids,
                                _token: "{{ csrf_token() }}",
                            },
                            success: function(response) {
                                swal({
                                    title: "Success!",
                                    text: "Selected Grade have been deleted!",
                                    icon: "success",
                                });
                                setTimeout(function() {
                                    window.location.href = "{{url('grade')}}";
                                }, 2000);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    }
                }).then((willCancel) => {
                    if (willCancel) {
                        window.location.href = "{{url('grade')}}";
                    }
                });
            } else {
                swal({
                    title: "Error!",
                    text: "No items selected.",
                    icon: "error",
                });
            }
        });
    </script>

    <!-- Include footer or additional scripts -->
    @include("admin.include.footer")
</body>
</html>
