@extends('layouts.master')

@section('title', 'Employee Detail')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons"
                style="vertical-align: -4px">person</i>{{ __('messages.DetailEmployeeDetail') }}
        </h4>
    </div>


    <div class="shadow p-3 my-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="row">

            <div class="col-md-12">

                <div class="container bootstrap snippets bootdey">
                    <div class="panel-body inf-content">
                        <div class="row">
                            <div class="col-md-3">


                                <div class="card-body text-center">
                                    <!-- Profile picture image-->
                                    <img class="img-account-profile rounded-circle mb-2"
                                        src="{{ Storage::url('employees/photos/' . $employee->photo) }}"
                                        alt="employee photo">
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4"> <label for="">Employee ID :
                                            {{ $employee->employee_id }}</label> </div>

                                    <a class="btn create" href="{{ route('employees.index', ['page' => request()->input('page')]) }}">
                                        Back to index
                                    </a>
                                </div>

                            </div>
                            <div class="col-md-7 ms-4">
                                <strong>{{ __('messages.CreateInformationAboutEmployee') }} </strong> <i
                                    class="material-icons" style="vertical-align: -4px">person</i> <br><br>
                                <div class="table-responsive">
                                    <table class="table table-user-information">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <label for="id"
                                                        class="form-label">{{ __('messages.CreateId') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->employee_id }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="name"
                                                        class="form-label">{{ __('messages.CreateName') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="email"
                                                        class="form-label">{{ __('messages.CreateEmail') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->email }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label for="phone"
                                                        class="form-label">{{ __('messages.CreatePhone') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->phone }}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <label for="dateOfBirth"
                                                        class="form-label">{{ __('messages.CreateDateOfBirth') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->dateOfBirth }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="nrc"
                                                        class="form-label">{{ __('messages.CreateNRC') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->nrc }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateGender') }} </label>
                                                </td>
                                                <td class="text-primary">
                                                    @if ($employee->gender == 1)
                                                        Male
                                                    @elseif ($employee->gender == 2)
                                                        Female
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <label for="address"
                                                        class="form-label">{{ __('messages.CreateAddress') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    {{ $employee->address }}
                                                </td>
                                            </tr>


                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateLanguage') }} </label>
                                                </td>
                                                <td class="text-primary">
                                                    @php $languages = explode(', ', $employee->language) @endphp
                                                    @if (in_array(1, $languages))
                                                        English
                                                    @endif
                                                    @if (in_array(2, $languages))
                                                        Japan
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateCareer') }} </label>
                                                </td>
                                                <td class="text-primary">

                                                    @if ($employee->career_id == 1)
                                                        FrontEnd
                                                    @elseif ($employee->career_id == 2)
                                                        BackEnd
                                                    @elseif ($employee->career_id == 3)
                                                        FullStack
                                                    @elseif ($employee->career_id == 4)
                                                        Mobile
                                                    @endif

                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateLevel') }} </label>
                                                </td>
                                                <td class="text-primary">
                                                    @if ($employee->level_id == 1)
                                                        Begineer Engineer
                                                    @elseif ($employee->level_id == 2)
                                                        Junior Engineer
                                                    @elseif ($employee->level_id == 3)
                                                        Engineer
                                                    @elseif ($employee->level_id == 4)
                                                        Senior Engineer
                                                    @endif
                                                </td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label>{{ __('messages.CreateProgramingLanguage') }}</label>
                                                </td>
                                                <td class="text-primary">
                                                    @if (in_array(1, $progs))
                                                        Java
                                                    @endif
                                                    @if (in_array(2, $progs))
                                                        C++
                                                    @endif
                                                    @if (in_array(3, $progs))
                                                        PHP
                                                    @endif
                                                    @if (in_array(4, $progs))
                                                        React
                                                    @endif
                                                    @if (in_array(5, $progs))
                                                        Laravel
                                                    @endif
                                                    @if (in_array(6, $progs))
                                                        Android
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
