@extends('layouts.master')

@section('title', 'Employees Project Documentations')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons" style="vertical-align: -4px">work</i>
            {{ __('messages.projectShowEmployeesAssign') }}
        </h4>
        <div class="row">
            <div class="col-md-12">
                @if (Session::has('wrongAdmin'))
                    <div class="alert alert-warning alert-dismissible fade show col-md-5" role="alert">
                        {{ Session::get('wrongAdmin') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <div class="shadow p-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="d-flex align-items-center flex-wrap mb-4">

            <div class="col-md-4">
                <a class="btn create btn-sm my-2 me-2 shadow"
                    href="{{ route('employees.projects') }}">{{ __('messages.projectShowCreateProjectAssign') }}</a>

                <a class="btn btn-sm create" href="{{ route('employees.index', ['page' => $page]) }}">
                    Back to index
                </a>
            </div>

            <div class="dropdown col-md-8">
                <div class="dropdown d-flex justify-content-end">
                    <button class="btn btn-sm create dropdown-toggle shadow me-2" type="button" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        {{ __('messages.projectShowProjectTypes') }}
                    </button>
                    <ul class="dropdown-menu text-end">
                        <li><a class="dropdown-item text-center"
                                href="{{ route('employees.projectsShow', $employee_id) }}">{{ __('messages.projectShowAll') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center"
                                href="{{ route('employees.projectsShow', [$employee_id, 'no' => 1]) }}">{{ __('messages.projectShowOngoing') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-center"
                                href="{{ route('employees.projectsShow', [$employee_id, 'no' => 2]) }}">{{ __('messages.projectShowUpcomming') }}</a>
                        </li>
                        <li>
                            <hr class="dropdown-divider" text-center>
                        </li>
                        <li><a class="dropdown-item text-center"
                                href="{{ route('employees.projectsShow', [$employee_id, 'no' => 3]) }}">{{ __('messages.projectShowExpired') }}</a>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

        <div class="d-flex align-items-center justify-content-center mb-4">
            <p class="text-dark my-3 me-auto"><i class="material-icons"
                    style="vertical-align: -4px">person</i>{{ __('messages.projectShowEmployeeId') }}:
                {{ $employee_id }}</p>

            <a class="btn btn-sm me-2 shadow ms-auto document"
                href="{{ route('employees.downloadDocument', $employee_id) }}">
                <i class="material-icons">download_for_offline</i>
                {{ __('messages.projectShowDocument') }}
            </a>
        </div>

        <p style="color: #000080">{{ __('messages.TotalNumberOfProjects') }} : <b>{{ $totalProjects }}</b> </p><br>

        @php
            $count = 1;
        @endphp

        @if (Session::has('NoAssign'))
            <div class="alert alert-warning" role="alert">
                {{ Session::get('NoAssign') }}
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover table-condensed">
                    <thead>
                        <tr>
                            <th>{{ __('messages.projectShowNo') }}</th>
                            <th>{{ __('messages.projectShowName') }}</th>
                            <th>{{ __('messages.projectShowDocument') }}</th>
                            <th>{{ __('messages.projectShowStartDate') }}</th>
                            <th>{{ __('messages.projectShowEndDate') }}</th>
                            <th class="text-center">{{ __('messages.projectShowDowunloadDocument') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employeeProjectsDocs as $employeeProjectDoc)
                            <tr>
                                <td>{{ $count++ }}</td>
                                <td>{{ $employeeProjectDoc->name }}</td>
                                <td>{{ $employeeProjectDoc->filename }}</td>
                                <td>{{ $employeeProjectDoc->start_date }}</td>
                                <td>{{ $employeeProjectDoc->end_date }}</td>
                                <td class="text-center">
                                    <a class="btn btn-sm ms-2 shadow document"
                                        href="{{ route('employees.projects.individualDownload', $employeeProjectDoc->id) }}">
                                        <i class="material-icons">
                                            download_for_offline
                                        </i>
                                        {{ __('messages.projectShowDownload') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection
