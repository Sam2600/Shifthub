@extends('layouts.master')

@section('title', 'Project Assign')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons"
                style="vertical-align: -4px">person</i>{{ __('messages.projectAssignProjectAssign') }}</h4>
    </div>

    <div class="shadow p-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="col-md-8 offset-md-3">

            {{-- error messages and success messages divs --}}

            <div id="failMessageDiv"></div>
            <div id="successMessageDiv"></div>

            <div id="projectAdded"></div>
            <div id="projectAddedFailed"></div>

            <div id="projectRemoved"></div>
            <div id="projectRemovedFailed"></div>
            <div id="noProjectFound"></div>


            <form action="" id="register-form" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="text-center mb-4" id="noEmployeeWarning"></div>

                <div class="row mb-4">
                    <label for="id" class="col-md-2 col-form-label">{{ __('messages.projectShowEmployeeId') }}<span
                            class="required">*</span></label>

                    <div class="col-md-6">

                        @php
                            $count = count($employees);
                        @endphp

                        <select id="selectEmployeeLists"
                            class="form-select  @if ($errors->has('employee_id')) is-invalid @endif"
                            aria-label="Default select example" name="employee_id">

                            <option value="">{{ __('messages.projectShowEmployeeId') }}</option>

                            @foreach ($employees as $employee)
                                <option value="{{ $employee->employee_id }}"
                                    {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                    {{ $employee->employee_id }}
                                </option>
                            @endforeach

                        </select>

                        <span class="text-danger" id="employeeIdError"></span>

                    </div>
                </div>

                <div class="row mb-4">
                    <label for="name" class="col-md-2 col-form-label">{{ __('messages.projectShowName') }}<span
                            class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                            id="name" name="name" value="{{ old('name') }}" readonly>
                        <span class="text-danger" id="nameError"></span>

                    </div>
                </div>

                <div class="row mb-4">
                    <label for="name" class="col-md-2 col-form-label">{{ __('messages.projectShowProjectName') }}<span
                            class="required">*</span></label>
                    <div class="col-md-6">
                        <div class="input-group">

                            <select id="selectProjectLists"
                                class="form-select @if ($errors->has('project')) is-invalid @endif" name="project">
                            </select>

                            <button type="button" id="projectAssignButton" class="btn btn-outline-primary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#projectAssignModal">
                                +
                            </button>

                            <button type="button" id="projectRemoveButton" class="btn btn-outline-secondary btn-sm"
                                data-bs-toggle="modal" data-bs-target="#projectRemoveModal">
                                -</button>
                        </div>
                        <span class="text-danger" id="projectError"></span>

                    </div>
                </div>

                <div class="row mb-4">
                    <label for="startDate"
                        class="col-md-2 col-form-label">{{ __('messages.projectShowProjectStartDate') }}<span
                            class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="date" class="form-control @if ($errors->has('startDate')) is-invalid @endif"
                            id="startDate" name="startDate" value="{{ old('startDate') }}">
                        <span class="text-danger" id="startDateError"></span>

                    </div>
                </div>

                <div class="row mb-4">
                    <label for="endDate"
                        class="col-md-2 col-form-label">{{ __('messages.projectShowProjectEndDate') }}<span
                            class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="date" class="form-control @if ($errors->has('endDate')) is-invalid @endif"
                            id="endDate" name="endDate" value="{{ old('endDate') }}">
                        <span class="text-danger" id="endDateError"></span>

                    </div>
                </div>

                <div class="row mb-4">
                    <label for="document"
                        class="col-md-2 col-form-label">{{ __('messages.projectShowProjectDocument') }}<span
                            class="required">*</span></label>
                    <div class="col-md-6">
                        <input type="file" class="form-control @if ($errors->has('document')) is-invalid @endif"
                            id="document" name="document[]" value="{{ old('document') }}" multiple>

                        <span class="text-danger" id="documentError"></span>
                        <span class="text-danger" id="documentSizeError"></span>

                    </div>
                </div>

                <div>
                    <button type="submit" id="saveButton" class="btn save shadow my-3">
                        <span class="btn-txt">{{ __('messages.projectShowSaveButton') }}</span>
                    </button>
                    <a class="btn resetDisable reset ms-3 shadow my-3"
                        href="{{ route('employees.projects') }}">{{ __('messages.projectShowCloseButton') }}</a>
                </div>
            </form>


            <!-- Modal for project assign -->
            <div class="modal fade" id="projectAssignModal" tabindex="-1" aria-labelledby="myModalLabel1"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                <strong>{{ __('messages.projectShowProjectCreate') }}</strong>
                            </h1>
                            <button type="button" class="btn-close" id="close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="error_messages">

                            </div>
                            <div class="row">
                                <div class="col-md-auto text-center my-2">
                                    <label for="project"
                                        class="form-label">{{ __('messages.projectShowProjectName') }}<span
                                            class="required">*</span></label>
                                </div>
                                <div class="col-md text-center">
                                    <input type="text" class="form-control" name="project" id="project" />
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary reset" id="close"
                                data-bs-dismiss="modal">{{ __('messages.projectShowCloseButton') }}</button>
                            <button type="submit" class="btn btn-primary save"
                                id="projectSave">{{ __('messages.projectShowSaveButton') }}</button>
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
                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                <strong>{{ __('messages.projectShowChooseProjectToRemove') }}</strong>
                            </h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" id="close"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">

                            <div id="error_message_remove">
                            </div>

                            <label for="project"
                                class="form-label me-4">{{ __('messages.projectShowRemoveProject') }}<span
                                    class="required">*</span></label>

                            <br><br>

                            <select id="removeProjectLists" class="form-select scroll" name="project">
                            </select>

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary reset" data-bs-dismiss="modal"
                                id="close">{{ __('messages.projectShowCloseButton') }}</button>
                            <button type="submit" class="btn btn-primary save"
                                id="projectRemove">{{ __('messages.projectShowRemoveButton') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('script')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        // This is the process of disabling the modal form without clicking the button
        $(window).on('load', function() {
            $("#projectRemoveModal").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });

        $(window).on('load', function() {
            $("#projectAssignModal").modal({
                backdrop: 'static',
                keyboard: false,
                show: true
            });
        });

        $(document).ready(function() {

            // initializing the fetchAllData
            fetchAllData();

            // getting all data of employee-projects and projects table it is the same like getting query datas and importing to view
            function fetchAllData() {

                // Store the selected values before clearing the options
                var selectedEmployee = $("#selectEmployeeLists").val();
                var selectedProject = $("#selectProjectLists").val();
                var selectedRemoveProject = $("#removeProjectLists").val();

                $.ajax({

                    type: "GET",
                    url: "fetchDatas",
                    dataType: "json",
                    success: function(response) {

                        //console.log(response)

                        $("#selectProjectLists").html("");
                        $("#removeProjectLists").html("");

                        //We check the projects are exists or not. If not we disable the project select box
                        var project_count = response.projectCount

                        if (project_count === 0) {
                            $("#selectProjectLists").attr('disabled', 'disabled');
                            $("#projectRemoveButton").attr('disabled', 'disabled');

                        } else { // This situation is once there is an project we undo the disabled

                            $("#selectProjectLists").removeAttr('disabled');
                            $("#projectRemoveButton").removeAttr('disabled');
                        }

                        // This process is creating the default option tag
                        var projectOption = $('<option>');
                        projectOption.val("");
                        projectOption.text("Projects");
                        $("#selectProjectLists").append(projectOption);


                        // This process is looping and creating select options with the data we get from fetchAllData method
                        $.each(response.projects, function(key, values) {
                            var projectOption = $('<option>');
                            projectOption.val(values.id);
                            projectOption.text(values.name);
                            $("#selectProjectLists").append(projectOption);
                        });


                        // This process is creating the default option tag
                        var removeProjectOption = $('<option>');
                        removeProjectOption.val("");
                        removeProjectOption.text("Projects");
                        $("#removeProjectLists").append(removeProjectOption);


                        // This process is looping and creating select options with the data we get from fetchAllData method
                        $.each(response.projects, function(key, values) {
                            var removeProjectOption = $('<option>');
                            removeProjectOption.val(values.id);
                            removeProjectOption.text(values.name);
                            $("#removeProjectLists").append(removeProjectOption);
                        });

                        // Re-select the previously selected values
                        $("#selectEmployeeLists").val(selectedEmployee);

                        $("#selectProjectLists").val(selectedProject);

                        $("#removeProjectLists").val(selectedRemoveProject);
                    }
                });
            }


            // This process is to make the dynamic name input's value after changing the employee_id select option
            $(document).on("change", "#selectEmployeeLists", function() {

                var empId = $("#selectEmployeeLists").val()

                if (empId == "") {
                    $("#name").val("")
                }

                var data = {
                    "employee_id": empId
                }

                $.ajax({
                    type: "GET",
                    url: "getNames",
                    data: data,
                    dataType: "json",
                    success: function(response) {

                        $.each(response.name, function(key, value) {

                            var name = $("#name").val(value.name)

                            console.log($("#name").val())
                        });
                    }
                });

            });


            // project save with jquery
            $(document).on('click', '#projectSave', function() {

                var projectName = {

                    "project": $("#project").val(),
                }

                $.ajax({
                    type: "post",
                    url: "add",
                    data: projectName,
                    dataType: "json",

                    success: function(response) {

                        if (response.status === 400) {

                            $("#error_messages").html("")
                            $("#error_messages").addClass(
                                "alert alert-danger col-md-8 text-center alert-dismissible fade show"
                            )

                            $.each(response.message, function(key, value) {

                                $("#error_messages").text(value)
                            });

                        }

                        if (response.status === 200) {

                            $("#projectAdded").html("").removeClass().removeAttr('style');
                            $("#projectAdded").addClass(
                                "alert alert-success col-md-8 text-center alert-dismissible fade show"
                            )
                            $("#projectAdded").text(response.message)

                            $("#projectAssignModal").modal("hide")
                            $("#projectAssignModal").find("input").val("")

                            $("#projectAdded").fadeOut(7500)

                            fetchAllData();

                        }

                        if (response.status === 404) {

                            $("#projectAddedFailed").html("").removeClass().removeAttr('style');
                            $("#projectAddedFailed").addClass(
                                "alert alert-warning col-md-8 text-center alert-dismissible fade show"
                            )

                            $("#projectAddedFailed").text(response.message)

                            $("#projectAssignModal").modal("hide")
                            $("#projectAssignModal").find("input").val("")

                            $("#projectAddedFailed").fadeOut(7500)

                            fetchAllData();

                        }
                    }
                });

            });


            // This process is to diable the error message of projectAssignModel's
            $(document).on("click", "#close", function() {
                $("#error_messages").html("")
                $("#error_messages").empty();
                $("#error_messages").removeClass();

                $("#error_message_remove").html("")
                $("#error_message_remove").empty();
                $("#error_message_remove").removeClass();
            });


            // project remove with jquery
            $(document).on('click', '#projectRemove', function() {

                var removeProject = {
                    "project": $('#removeProjectLists').val(),
                }

                $.ajax({
                    type: "post",
                    url: "remove",
                    data: removeProject,
                    dataType: "json",
                    success: function(response) {

                        if (response.status === 400) {

                            $("#error_message_remove").html("")
                            $('#error_message_remove').addClass(
                                "alert alert-danger text-center")

                            $.each(response.message, function(key, value) {

                                $('#error_message_remove').text(value);

                            });

                        }

                        if (response.status === 404) {

                            $("#noProjectFound").html("").removeClass().removeAttr('style');
                            $("#noProjectFound").addClass(
                                "alert alert-warning col-md-8 text-center alert-dismissible fade show"
                            )

                            $("#noProjectFound").text(response.message)

                            $("#projectRemoveModal").modal("hide")
                            $("#projectRemoveModal").find("input").val("")

                            $("#noProjectFound").fadeOut(7500)

                            $("#selectProjectLists").val(null)

                            fetchAllData();
                        }

                        if (response.status === 200) {

                            $("#projectRemoved").html("").removeClass().removeAttr('style');
                            $("#projectRemoved").addClass(
                                "alert alert-success col-md-8 text-center alert-dismissible fade show"
                            )

                            $("#projectRemoved").text(response.message)

                            $("#projectRemoveModal").modal("hide")
                            $("#projectRemoveModal").find("input").val("")

                            $("#projectRemoved").fadeOut(7500)

                            $("#selectProjectLists").val(null)

                            fetchAllData();

                        }

                        if (response.status === 403) {

                            $("#projectRemovedFailed").html("").removeClass().removeAttr(
                                'style');
                            $("#projectRemovedFailed").addClass(
                                "alert alert-warning col-md-8 text-center alert-dismissible fade show"
                            )

                            $("#projectRemovedFailed").text(response.message)

                            $("#projectRemoveModal").modal("hide")
                            $("#projectRemoveModal").find("input").val("")

                            $("#projectRemovedFailed").fadeOut(7500)

                            fetchAllData();

                        }
                    }
                });
            });


            // form submit for employee-assign
            $(document).on("submit", "#register-form", function(e) {

                e.preventDefault();

                var formData = new FormData(this);

                $("#spinner").removeClass("hidee");
                $("#saveButton").attr('disabled', true);
                $("#saveButton").text("Loading ...");
                $(".resetDisable").addClass('disabled');

                $.ajax({
                    type: "POST",
                    url: "{{ route('employees.projectAssign') }}",
                    data: formData,
                    dataType: "json",
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        function clearErrorMessages() {
                            $("#employeeIdError").text('');
                            $("#nameError").text('');
                            $("#projectError").text('');
                            $("#startDateError").text('');
                            $("#endDateError").text('');
                            $("#documentError").text('');
                            $("#documentSizeError").text('');
                        }

                        if (response.status == 1) {

                            $("#failMessageDiv").html("").removeClass().removeAttr('style');
                            $("#failMessageDiv").addClass(
                                'alert alert-danger col-md-8 text-center alert-dismissible fade show'
                            )
                            $("#failMessageDiv").text(response.message);

                            clearErrorMessages();

                            $("#failMessageDiv").fadeOut(7500)

                        }

                        if (response.status == 200) {

                            $("#successMessageDiv").html("").removeClass().removeAttr('style');
                            $("#successMessageDiv").addClass(
                                'alert alert-success col-md-8 text-center alert-dismissible fade show'
                            )
                            $("#successMessageDiv").text(response.message);

                            $("#register-form").find("input").val("")
                            $("#selectEmployeeLists").val("")
                            $("#selectProjectLists").val(null)

                            clearErrorMessages();

                            $("#successMessageDiv").fadeOut(7500)

                        }

                        if (response.status == 3) {

                            $("#failMessageDiv").html("").removeClass().removeAttr('style');
                            $("#failMessageDiv").addClass(
                                'alert alert-danger col-md-8 text-center alert-dismissible fade show'
                            )
                            $("#failMessageDiv").text(response.message);

                            clearErrorMessages();

                            $("#failMessageDiv").fadeOut(7500)
                        }

                        if (response.status == 4) {

                            $("#failMessageDiv").html("").removeClass().removeAttr('style');
                            $("#failMessageDiv").addClass(
                                'alert alert-danger col-md-8 text-center alert-dismissible fade show'
                            )
                            $("#failMessageDiv").text(response.message);

                            clearErrorMessages();

                            $("#failMessageDiv").fadeOut(7500)
                        }
                    },

                    complete: function() {
                        $("#spinner").addClass("hidee")
                        $("#saveButton").removeAttr("disabled")
                        $("#saveButton").text("Save");
                        $(".resetDisable").removeClass('disabled')
                    },

                    error: function(error) {

                        let err = error.responseJSON;

                        if (err.errors.employee_id) {
                            $("#employeeIdError").text(err.errors.employee_id);
                        } else {
                            $("#employeeIdError").text('');
                        }

                        if (err.errors.name) {
                            $("#nameError").text(err.errors.name);
                        } else {
                            $("#nameError").text('');
                        }

                        if (err.errors.project) {
                            $("#projectError").text(err.errors.project);
                        } else {
                            $("#projectError").text('');
                        }

                        if (err.errors.startDate) {
                            $("#startDateError").text(err.errors.startDate);
                        } else {
                            $("#startDateError").text('');
                        }

                        if (err.errors.endDate) {
                            $("#endDateError").text(err.errors.endDate);
                        } else {
                            $("#endDateError").text('');
                        }

                        if (err.errors.document) {
                            $("#documentError").text(err.errors.document);
                        } else {
                            $("#documentError").text('');
                        }

                        if (err.errors && err.errors["document.0"]) {
                            var response = err.errors["document.0"][0];
                            $("#documentSizeError").text(response);
                        } else {
                            $("#documentSizeError").text('');
                        }

                    }

                });

            });

        });
    </script>

@endsection
