@extends('layouts.master')

@section('title', 'Employees Index')

@section('content')


    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons groups">groups</i>{{ __('messages.indexEmployees') }}</h4>
        @if (Session::has('loginMessage'))
            <div class="alert alert-success alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('loginMessage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('noProject'))
            <div class="alert alert-warning alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('noProject') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('noEmployeeMessage'))
            <div class="alert alert-warning alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('noEmployeeMessage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('deleteMessage'))
            <div class="alert alert-success text-center alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('deleteMessage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('NoProjects'))
            <div class="alert alert-warning text-center alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('NoProjects') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('noProject'))
            <div class="alert alert-warning alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('noProject') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('storeMessage'))
            <div class="alert alert-success text-center alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('storeMessage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('updateMessage'))
            <div class="alert alert-success text-center alert-dismissible fade show col-md-5" role="alert">
                {{ Session::get('updateMessage') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>


    <div class="shadow p-5 bg-white rounded" style="background: #e8ebe8">

        <div class="d-flex align-items-center mb-4">

            <a class="btn create btn-sm my-2 mb-4 shadow"
                href="{{ route('employees.create') }}">{{ __('messages.indexCreateEmployee') }} </a>

            <div class="col-sm-6 ms-3 ms-auto">
                <form action="{{ route('employees.index') }}" method="GET">
                    <div class="input-group mb-3">
                        <div class="row">
                            <div class="col-md-3 ms-3">
                                <input type="text" name="employee_id" class="form-control"
                                    placeholder="{{ __('messages.indexEmployeeId') }}"
                                    @if ($counts === 0) disabled @endif
                                    @if (Session::has('employee_id')) value="{{ Session::get('employee_id') }}" @endif>
                            </div>
                            <div class="col-md-3 ms-3">
                                <select class="form-select custom-select" id="career_select" name="career"
                                    @if ($counts === 0) disabled @endif>
                                    <option value="0">{{ __('messages.indexCareer') }}</option>
                                    @foreach ($careers as $career)
                                        <option value="{{ $career }}"
                                            @if (Session::get('career') == $career) selected @endif>
                                            @if ($career == 1)
                                                FrontEnd
                                            @elseif($career == 2)
                                                BackEnd
                                            @elseif($career == 3)
                                                Fullstack
                                            @else
                                                Mobile
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 ms-3">
                                <select class="form-select" id="level_select" name="level"
                                    @if ($counts === 0) disabled @endif>
                                    <option value="0">{{ __('messages.indexLevel') }}</option>
                                    @foreach ($levels as $level)
                                        <option value="{{ $level }}"
                                            @if (Session::get('level') == $level) selected @endif>
                                            @if ($level == 1)
                                                Beginner Engineer
                                            @elseif($level == 2)
                                                Junior Engineer
                                            @elseif($level == 3)
                                                Engineer
                                            @else
                                                Senior Engineer
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm" style="padding-right: 0px">
                                <button type="submit" class="search btn btn-outline-dark"
                                    @if ($counts === 0) disabled @endif>{{ __('messages.indexSearch') }}</button>
                            </div>
                        </div>
                    </div>
                </form>

            </div>
        </div>

        {{-- That is get from AllEmployee and it will not count the deleted_at emps --}}
        @if ($counts === 0)


            <div class="alert alert-danger col-md-6 text-center" role="alert">
                There is no employee yet <i class="material-icons"> sentiment_dissatisfied</i>. Please register
                employees <a href="{{ route('employees.create') }}" class="alert-link text-success">Here</a>
            </div>

            {{-- This count is search data query's count --}}
        @elseif (count($employees) === 0)
            <div class="alert alert-warning col-md-6 text-center" role="alert">
                There is no employees with that input search. Please try agian!
            </div>
        @else
            <div class="row align-items-center">
                <div class="col-md-3 me-auto">
                    <a class="a btn btn-sm mb-3 shadow ms-auto"
                        href="{{ route('employees.exportExcel', request()->query()) }}">
                        <i class="material-icons"> download </i>{{ __('messages.indexExportExcel') }}
                    </a>
                    <a class="a btn btn-sm mb-3 shadow ms-2" href="{{ route('employees.exportPdf', request()->query()) }}">
                        <i class="material-icons"> download </i>{{ __('messages.indexExportPdf') }}
                    </a>
                </div>

                <div class="col-md-3 ms-auto">
                    <p style="color: #000080" class="mb-4 ms-5">{{ __('messages.indexTotalNumberOfEmployees') }} :
                        <b>{{ $counts }}</b>
                    </p>
                </div>
            </div>

            @php
                $count = 1;
            @endphp

            <br>

            <div>
                <table class="table table-responsive table-hover table-condensed">
                    <thead>
                        <tr style="font-size: 14px">
                            <th>{{ __('messages.indexTableNo') }}</th>
                            <th>{{ __('messages.indexTableEmployeeId') }}</th>
                            <th>{{ __('messages.indexTableEmployeeName') }}</th>
                            <th>{{ __('messages.indexTableEmployeeEmail') }}</th>
                            <th>{{ __('messages.indexTableEmployeeCareer') }}</th>
                            <th>{{ __('messages.indexTableEmployeeLevel') }}</th>
                            <th>{{ __('messages.indexTableEmployeePhone') }}</th>
                            <th class="text-center" style="/*! width:280px; */">{{ __('messages.indexTableAction') }}
                            </th>
                            <th class="text-center" style="width:160px">{{ __('messages.indexTableEmployeeAssign') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($employees as $employee)
                            <tr class="small-row align-middle" style="font-size:14px">
                                <td>{{ $count++ }}</td>
                                <td>{{ $employee->employee_id }}</td>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->email }}</td>

                                @if ($employee->career_id === 1)
                                    <td>FrontEnd</td>
                                @elseif ($employee->career_id === 2)
                                    <td>BackEnd</td>
                                @elseif ($employee->career_id === 3)
                                    <td>FullStack</td>
                                @else
                                    <td>Mobile</td>
                                @endif

                                @if ($employee->level_id === 1)
                                    <td>Beginner Engineer</td>
                                @elseif ($employee->level_id === 2)
                                    <td>Junior Engineer</td>
                                @elseif ($employee->level_id === 3)
                                    <td>Engineer</td>
                                @else
                                    <td>Senior Engineer</td>
                                @endif

                                <td>{{ $employee->phone }}</td>

                                <td>

                                    <div class="inline-buttons">
                                        <a class="btn detail btn-sm shadow"
                                            href="{{ route('employees.view', $employee->id) }}"><i
                                                class="material-icons">account_circle</i>{{ __('messages.indexDetail') }}</a>
                                        <a class="btn edit btn-sm ms-2 shadow"
                                            href="{{ route('employees.edit', $employee->id) }}"><i
                                                class="material-icons">edit</i>
                                            {{ __('messages.indexUpdate') }}</a>

                                        <button type="button" class="btn remove ms-2 btn-sm shadow"
                                            data-bs-toggle="modal" data-bs-target="#removeModal">
                                            <i class="material-icons">delete</i>{{ __('messages.indexRemove') }}
                                        </button>

                                        <div class="modal fade" id="removeModal" tabindex="-1"
                                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                            <strong>{{ __('messages.indexEmployeeRemove') }}</strong></h1>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <div class="modal-body">
                                                        {{-- Are you sure you want to <b>remove</b> this employee?  --}}
                                                        {{ __('messages.indexEmployeeAreYouSureYouWantToRemoveThisEmployee') }}
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary reset"
                                                            data-bs-dismiss="modal">{{ __('messages.indexNoButton') }}</button>
                                                        <form action="{{ route('employees.delete', $employee->id) }}"
                                                            method="POST" class="inline-form">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" name="submit"
                                                                class="btn btn-primary save">{{ __('messages.indexYesButton') }}</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </td>

                                <td><a class="btn btn-sm ms-2 shadow document"
                                        href="{{ route('employees.projectsShow', $employee->id) }}"><i
                                            class="material-icons">
                                            work
                                        </i>
                                        {{ __('messages.indexViewAssign') }}</a></td>
                            </tr>
                        @endforeach
        @endif
        </tbody>
        </table>
    </div>
    {!! $employees->links() !!}
    </div>
@endsection
