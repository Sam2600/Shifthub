@extends('layouts.master')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons groups">groups</i>{{ __('messages.indexEmployees') }}</h4>
        <!-- Message alerts -->
        <div class="messageContainer">

            @if (Session::has('loginMessage'))
                <div class="alert alert-success text-center alert-dismissible fade show col-md-5" role="alert">
                    {{ Session::get('loginMessage') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('wrongAdmin'))
                <div class="alert alert-warning text-center alert-dismissible fade show col-md-5" role="alert">
                    {{ Session::get('wrongAdmin') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('noProject'))
                <div class="alert alert-warning text-center alert-dismissible fade show col-md-5" role="alert">
                    {{ Session::get('noProject') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('noEmployeeMessage'))
                <div class="alert alert-warning text-center alert-dismissible fade show col-md-5" role="alert">
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

        <!-- Employee table -->
        <div class="shadow p-4 bg-white rounded" style="background: #e8ebe8">

            <div class="row mt-4">
                <div class="col-md-4">
                    <a class="btn create btn-sm my-2 mb-4 shadow ms-3 text-start"
                        href="{{ route('employees.create', request()->query()) }}">{{ __('messages.indexCreateEmployee') }}</a>
                </div>

                <div class="col-md-8">
                    <form action="{{ route('employees.index', request()->query()) }}" method="GET">
                        <div class="input-group mb-3">
                            <div class="row align-items-end ms-auto">
                                <div class="col-md-3 ms-1 me-1 mt-2">
                                    <input type="text" name="employee_id" class="form-control"
                                        placeholder="{{ __('messages.indexEmployeeId') }}"
                                        @if ($total === 0) disabled @endif
                                        value="{{ request()->input('employee_id') ?? null }}">
                                </div>
                                <div class="col-md-3 ms-1 me-1 mt-2 mt-md-0">
                                    <select class="form-select custom-select" id="career_select" name="career"
                                        @if ($total === 0) disabled @endif>
                                        <option value="0">{{ __('messages.indexCareer') }}</option>
                                        @foreach ($careers as $career)
                                            <option value="{{ $career }}"
                                                {{ request()->input('career') == $career ? 'selected' : null }}>
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
                                <div class="col-md-3 ms-1 me-1 mt-2 mt-md-0">
                                    <select class="form-select" id="level_select" name="level"
                                        @if ($total === 0) disabled @endif>
                                        <option value="0">{{ __('messages.indexLevel') }}</option>
                                        @foreach ($levels as $level)
                                            <option value="{{ $level }}"
                                                {{ request()->input('level') == $level ? 'selected' : null }}>
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
                                <div class="col-md-2 ms-1 me-1 mt-2">
                                    <button type="submit" class="search btn btn-outline-dark"
                                        @if ($total === 0) disabled @endif>{{ __('messages.indexSearch') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Storing session of current page in index --}}
            {{ Session::put('currentPage', request()->input('page')) }}


            <div class="row align-items-center my-4">

                <div class="col-md-6">
                    @if ($counts > 0)
                        <a class="a btn btn-sm mb-3 ms-3 shadow"
                            href="{{ route('employees.exportExcel', request()->query()) }}">
                            <i class="material-icons">download</i>{{ __('messages.indexExportExcel') }}
                        </a>
                        <a class="a btn btn-sm mb-3 shadow ms-2"
                            href="{{ route('employees.exportPdf', request()->query()) }}">
                            <i class="material-icons">download</i>{{ __('messages.indexExportPdf') }}
                        </a>
                    @endif
                </div>
                <div class="col-md-6 text-end">
                    <p style="color: #000080" class="mb-4 me-5">{{ __('messages.indexTotalNumberOfEmployees') }}:
                        <b>{{ $total }}</b>
                    </p>
                </div>
            </div>

            @if ($total === 0)
                <div class="alert alert-danger col-md-6 text-center" role="alert">
                    There are no employees yet <i class="material-icons">sentiment_dissatisfied</i>. Please register
                    employees <a href="{{ route('employees.create', request()->input('page')) }}"
                        class="alert-link text-success">Here</a>
                </div>
            @elseif ($counts === 0)
                <div class="alert alert-warning col-md-6 text-center" role="alert">
                    There are no employees matching the search criteria. Please try again!
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover table-condensed">
                        <thead>
                            <tr>
                                <th>{{ __('messages.indexTableNo') }}</th>
                                <th>{{ __('messages.indexTableEmployeeId') }}</th>
                                <th>{{ __('messages.indexTableEmployeeName') }}</th>
                                <th>{{ __('messages.indexTableEmployeeEmail') }}</th>
                                <th>{{ __('messages.indexTableEmployeeCareer') }}</th>
                                <th>{{ __('messages.indexTableEmployeeLevel') }}</th>
                                <th>{{ __('messages.indexTableEmployeePhone') }}</th>
                                <th class="text-center">{{ __('messages.indexTableAction') }}</th>
                                <th class="text-center">{{ __('messages.indexTableEmployeeAssign') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees as $employee)
                                <tr class="small-row align-middle">
                                    <td>{{ $loop->iteration + ($employees->currentPage() - 1) * $employees->perPage() }}
                                    </td>
                                    <td>{{ $employee->employee_id }}</td>
                                    <td>{{ $employee->name }}</td>
                                    <td>{{ $employee->email }}</td>
                                    <td>{{ $employee->career }}</td>
                                    <td>{{ $employee->level }}</td>
                                    <td>{{ $employee->phone }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">

                                            <a class="btn btn-sm detail shadow me-2"
                                                href="{{ route('employees.view', $employee->id) }}">
                                                <i
                                                    class="material-icons">account_circle</i>{{ __('messages.indexDetail') }}
                                            </a>
                                            <a class="btn btn-sm edit shadow me-2"
                                                href="{{ route('employees.edit', $employee->id) }}">
                                                <i class="material-icons">edit</i>{{ __('messages.indexUpdate') }}
                                            </a>

                                            {{-- CHECK --}}
                                            <button type="button" class="btn btn-sm remove shadow me-2"
                                                data-bs-toggle="modal" data-bs-target="#removeModal{{ $employee->id }}">
                                                <i class="material-icons">delete</i>{{ __('messages.indexRemove') }}
                                            </button>

                                            <div class="modal fade" id="removeModal{{ $employee->id }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                <strong>{{ __('messages.indexEmployeeRemove') }}</strong>
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>

                                                        <div class="modal-body">
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
                                    <td class="text-center">
                                        <a class="btn btn-sm document shadow"
                                            href="{{ route('employees.projectsShow', ['id' => $employee->id, 'page' => request()->input('page')]) }}">
                                            <i class="material-icons">work</i>{{ __('messages.indexViewAssign') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination links -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
