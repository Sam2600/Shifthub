@extends('layouts.master')

@section('title', 'Project Assign')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons" style="vertical-align: -4px">person</i>Project Assign</h4>
    </div>

    <div class="shadow p-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="row">

            <div class="col-md-8 offset-md-2">

                <div class="col-md-8 offset-md-2">

                    @if (Session::has('ProjectAssignFailed'))
                        <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('ProjectAssignFailed') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('ProjectAssign'))
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('ProjectAssign') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('projectDeleteFail'))
                        <div class="alert alert-warning text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('projectDeleteFail') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('projectDeleteSuccess'))
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('projectDeleteSuccess') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('projectAddSuccess'))
                        <div class="alert alert-success text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('projectAddSuccess') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('DateIssues'))
                        <div class="alert alert-danger text-center alert-dismissible fade show" role="alert">
                            {{ Session::get('DateIssues') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif



                    <div class="messageDiv"></div>


                    <form action="{{ route('employees.projectAssign') }}" id="register-form" method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="text-center mb-4" id="noEmployeeWarning"></div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="id" class="form-label">Employee ID<span class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <select id="selectEmployeeLists" class="form-select" aria-label="Default select example"
                                    name="employee_id" onchange="updateNameField()">
                                </select>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="name" class="form-label">Name<span class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name"
                                    name="name" value="{{old('name')}}" disabled>

                                @error('name')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('name') }}</div>
                                @enderror

                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <span><label> Project Name<span class="required">*</span></label></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <div class="input-group">

                                    <select id="selectProjectLists" class="form-select" name="project">
                                        {{-- @if ($projectCount == 0) disabled @endif> --}}
                                    </select>

                                    <button type="button" id="projectAssignButton" class="btn btn-outline-primary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#projectAssignModal">
                                        +
                                    </button>

                                    <button type="button" id="projectRemoveButton" class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#projectRemoveModal">
                                        {{-- @if ($projectCount == 0) disabled @endif> --}}
                                        -</button>
                                </div>
                            </div>
                        </div>



                        <div class="d-flex align-items-center mb-4">
                            <label for="startDate" class="form-label">Start Date<span class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="date"
                                    class="form-control @if ($errors->has('startDate')) is-invalid @endif" id="startDate"
                                    name="startDate" value="{{ old('startDate') }}">
                                @error('startDate')
                                    <div id="startDateHelp" class="form-text text-danger">{{ $errors->first('startDate') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="endDate" class="form-label">End Date<span class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="date"
                                    class="form-control @if ($errors->has('endDate')) is-invalid @endif"
                                    id="endDate" name="endDate" value="{{ old('endDate') }}">
                                @error('endDate')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('endDate') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="document" class="form-label">Document<span class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="file"
                                    class="form-control @if ($errors->has('document')) is-invalid @endif"
                                    id="document" name="document[]" value="{{ old('document[]') }}" multiple>
                                @error('document')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('document') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <button type="submit" class="btn save btn-sm shadow">Save</button>
                            <button type="reset" class="btn reset btn-sm ms-3 shadow">Reset</button>
                        </div>
                    </form>
                </div>

                <!-- Modal for project assign -->
                <div class="modal fade" id="projectAssignModal" tabindex="-1" aria-labelledby="myModalLabel1"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"><strong>Project Create</strong></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="error_messages">

                                </div>
                                <div class="row">
                                    <div class="col-md-auto text-center my-2">
                                        <label for="project" class="form-label">Project name<span class="required">*</span></label>
                                    </div>
                                    <div class="col-md text-center">
                                        <input type="text" class="form-control" name="project" id="project" />
                                    </div>
                                </div>



                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary reset"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary save" id="projectSave">Save</button>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal for project remove -->
                <div class="modal fade" id="projectRemoveModal" tabindex="-1" aria-labelledby="projectRemoveModal"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel"><strong>Choose Projects to remove</strong></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">

                                <div id="error_message_remove">

                                </div>


                                <label for="project" class="form-label me-4">Remove Project<span
                                        class="required">*</span></label>

                                <br><br>

                                <select id="removeProjectLists" class="form-select scroll" name="project">

                                    {{-- <option value="">Projects</option> --}}
                                    {{-- @foreach ($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach --}}

                                </select>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary reset" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary save" id="projectRemove">Remove</button>
                                {{-- </form> --}}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')

    <script>



        function updateNameField() {
            var selectElement = document.querySelector('select.form-select');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var nameField = document.getElementById('name');
            nameField.value = selectedOption.value;
        }






        $(document).ready(function() {

            fetchAllData();

            // getting all data of employee-projects and projects table
            function fetchAllData() {

                // Store the selected values before clearing the options
                var selectedEmployee = $("#selectEmployeeLists").val();
                var selectedProject = $("#selectProjectLists").val();
                var selectedRemoveProject = $("#removeProjectLists").val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({

                    type: "GET",
                    url: "fetchDatas",
                    dataType: "json",
                    success: function(response) {

                        //console.log(response)

                        $("#selectEmployeeLists").html("");
                        $("#selectProjectLists").html("");
                        $("#removeProjectLists").html("");

                        var employee_count = response.employees.length

                        if (employee_count === 0) {

                            $("#selectEmployeeLists").attr('disabled', 'disabled')

                            $("#noEmployeeWarning").addClass("alert alert-warning").text(
                                "There is no employee yet to assign projects.")
                        }

                        var project_count = response.projectCount

                        if (project_count === 0) {
                            $("#selectProjectLists").attr('disabled', 'disabled');
                            $("#projectRemoveButton").attr('disabled', 'disabled');
                        } else {
                            $("#selectProjectLists").removeAttr('disabled');
                            $("#projectRemoveButton").removeAttr('disabled');
                        }

                        var newOption = $('<option>');
                        newOption.val("");
                        newOption.text("Employee Id");
                        $("#selectEmployeeLists").append(newOption);

                        $.each(response.employees, function(key, values) {
                            var newOption = $('<option>');
                            newOption.val(values.name);
                            newOption.text(values.employee_id);
                            $("#selectEmployeeLists").append(newOption);
                        });

                        var projectOption = $('<option>');
                        projectOption.val("");
                        projectOption.text("Projects");
                        $("#selectProjectLists").append(projectOption);

                        $.each(response.projects, function(key, values) {
                            var projectOption = $('<option>');
                            projectOption.val(values.id);
                            projectOption.text(values.name);
                            $("#selectProjectLists").append(projectOption);
                        });

                        var removeProjectOption = $('<option>');
                        removeProjectOption.val("");
                        removeProjectOption.text("Projects");
                        $("#removeProjectLists").append(removeProjectOption);

                        $.each(response.projects, function(key, values) {
                            var removeProjectOption = $('<option>');
                            removeProjectOption.val(values.id);
                            removeProjectOption.text(values.name);
                            $("#removeProjectLists").append(removeProjectOption);
                        });

                        // Re-select the previously selected values
                        $("#selectEmployeeLists").val(selectedEmployee);
                        $("#selectProjectLists").val(selectedProject);
                        //$("#removeProjectLists").val(selectedRemoveProject);
                    }
                });
            }


            // project save with jquery
            $(document).on('click', '#projectSave', function() {

                var projectName = {

                    "project": $("#project").val(),
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: "add",
                    data: projectName,
                    dataType: "json",

                    success: function(response) {

                        if (response.status === 400) {

                            $("#error_messages").html("")
                            $("#error_messages").addClass("alert alert-danger")

                            $.each(response.message, function(key, value) {

                                $("#error_messages").text(value)
                            });

                        }

                        if (response.status === 200) {

                            $(".messageDiv").addClass("alert alert-success")
                            $(".messageDiv").text(response.message)
                            $("#projectAssignModal").modal("hide")
                            $("#projectAssignModal").find("input").val("")
                            fetchAllData();

                        }

                        if (response.status === 404) {

                            $(".messageDiv").addClass("alert alert-warning")
                            $(".messageDiv").text(response.message)
                            $("#projectAssignModal").modal("hide")
                            $("#projectAssignModal").find("input").val("")

                        }
                    }
                });

            });

            // project remove with jquery
            $(document).on('click', '#projectRemove', function() {

                var removeProject = {
                    "project": $('#removeProjectLists').val(),
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: "remove",
                    data: removeProject,
                    dataType: "json",
                    success: function(response) {



                        if (response.status === 400) {

                            $("#error_message_remove").html("")
                            $('#error_message_remove').addClass("alert alert-danger")

                            $.each(response.message, function(key, value) {

                                $('#error_message_remove').text(value);

                            });

                        }

                        if (response.status === 200) {

                            $(".messageDiv").addClass("alert alert-success")
                            $(".messageDiv").text(response.message)
                            $("#projectRemoveModal").modal("hide")
                            $("#projectRemoveModal").find("input").val("")
                            fetchAllData();

                        }

                        if (response.status === 403) {

                            $(".messageDiv").addClass("alert alert-warning")
                            $(".messageDiv").text(response.message)
                            $("#projectRemoveModal").modal("hide")
                            $("#projectRemoveModal").find("input").val("")

                        }
                    }
                });
            });


        });
    </script>

@endsection
