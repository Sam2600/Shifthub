@extends('layouts.master')

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
                                    <img class="img-account-profile emp-img rounded-circle mb-2"
                                        src="{{ Storage::url('employees/photos/' . $employee->photo) }}"
                                        alt="employee photo">
                                    <!-- Profile picture help block-->
                                    <div class="small font-italic text-muted mb-4"> <label for="">Employee ID :
                                            {{ $employee->employee_id }}</label> </div>
                                    <a class="btn create" href="{{ $previous_url }}">
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
                                                <td class="text-primary">{{ $employee->gender }}</td>
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
                                                <td class="text-primary">{{ $employee->languages }}</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateCareer') }} </label>
                                                </td>
                                                <td class="text-primary">{{ $employee->career }}</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label> {{ __('messages.CreateLevel') }} </label>
                                                </td>
                                                <td class="text-primary">{{ $employee->level }}</td>
                                            </tr>

                                            <tr>
                                                <td>
                                                    <label>{{ __('messages.CreateProgramingLanguage') }}</label>
                                                </td>
                                                <td class="text-primary">{{ $employee->programming_languages }}</td>
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
