@extends('layouts.master')

@section('title', 'Employee Detail')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons" style="vertical-align: -4px">person</i>{{__("messages.DetailEmployeeDetail")}}
        </h4>
    </div>


    <div class="shadow p-3 my-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="row">

            <div class="col-md-8 offset-md-2">

                <div class="col-md-8 offset-md-2">

                    <form action="{{ route('employees.index') }}" method="GET" enctype="multipart/form-data">

                        @csrf

                        <div class="d-flex align-items-baseline mb-4 my-2">
                            {{-- <label for="photo" class="form-label">Photo</label> --}}
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <img src="{{ Storage::url('employees/photos/'.$employee->photo)}}" alt="employee photo">
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="id" class="form-label">{{__("messages.CreateId")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control" id="id" name="id" value="{{ $employee->employee_id }}" disabled>
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-4">
                            <label for="name" class="form-label">{{__("messages.CreateName")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control" id="name" name="name" value="{{ $employee->name }}" disabled>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="email" class="form-label">{{__("messages.CreateEmail")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="email"
                                    class="form-control" id="email" name="email" value="{{ $employee->email }}" disabled>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="phone" class="form-label">{{__("messages.CreatePhone")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control" id="phone" name="phone" value="{{ $employee->phone }}" disabled>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="dateOfBirth" class="form-label">{{__("messages.CreateDateOfBirth")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="date" class="form-control" id="date" name="dateOfBirth" value="{{ $employee->dateOfBirth }}" disabled>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="nrc" class="form-label">{{__("messages.CreateNRC")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text" class="form-control" id="nrc" name="nrc" value="{{ $employee->nrc }}" disabled>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <span><label> {{__("messages.CreateGender")}} </label></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input class="form-check-input" type="radio" name="gender" id="male" value="1" @if ($employee->gender == 1) checked @endif disabled>
                                <label class="form-check-label " for="gender">{{__("messages.CreateGenderMale")}}</label>

                                <input class="form-check-input ms-3" type="radio" name="gender" id="female" value="2" @if ($employee->gender == 2) checked @endif disabled>
                                <label class="form-check-label" for="gender">{{__("messages.CreateGenderFemale")}}</label>
                            </div>
                        </div>


                        <div class="d-flex align-items-center my-3 mb-4">
                            <label for="address" class="form-label">{{__("messages.CreateAddress")}}</label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <textarea class="form-control" id="address" name="address" rows="2" disabled>{{ $employee->address }}</textarea>
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{__("messages.CreateLanguage")}} </label></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="language" name="language[]" value="1"
                                        @php $languages = explode(', ', $employee->language) @endphp
                                        @if (in_array(1, $languages)) checked @endif disabled>
                                    <label class="form-check-label" for="language">{{__("messages.CreateLanguageEnglish")}}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="language" name="language[]" value="2"
                                        @php $languages = explode(', ', $employee->language) @endphp
                                        @if (in_array(2, $languages)) checked @endif disabled>
                                    <label class="form-check-label" for="language">{{__("messages.CreateLanguageJapan")}}</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{__("messages.CreateCareer")}} </label></span>

                            <div class="col-md-8 offset-md-2 ms-auto">
                                <select class="form-select" name="career" disabled>
                                    <option value="1" @if ($employee->career_id == 1) selected @endif>FrontEnd
                                    </option>
                                    <option value="2" @if ($employee->career_id == 2) selected @endif>BackEnd
                                    </option>
                                    <option value="3" @if ($employee->career_id == 3) selected @endif>FullStack
                                    </option>
                                    <option value="4" @if ($employee->career_id == 3) selected @endif>Mobile
                                    </option>
                                </select>
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{__("messages.CreateLevel")}} </label></span>

                            <div class="col-md-8 offset-md-2 ms-auto">
                                <select class="form-select" name="level" disabled>
                                    <option value="1" @if ($employee->level_id == 1) selected @endif>Beginner
                                    </option>
                                    <option value="2" @if ($employee->level_id == 2) selected @endif>Junior
                                        Engineer</option>
                                    <option value="3" @if ($employee->level_id == 3) selected @endif>Engineer
                                    </option>
                                    <option value="4" @if ($employee->level_id == 4) selected @endif>Senior
                                        Enginner</option>
                                </select>
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label>{{__("messages.CreateProgramingLanguage")}}</label></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="1"
                                            @if (in_array(1, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox1">C++</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="2"
                                            @if (in_array(2, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox2">Java</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="3"
                                            @if (in_array(3, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox3">PHP</label>
                                    </div>
                                </div>


                                <div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="4"
                                            @if (in_array(4, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox3">React</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="5"
                                            @if (in_array(5, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox3">Android</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input" type="checkbox" id="prog_lang" name="prog_lang[]" value="6"
                                            @if (in_array(6, $progs)) checked @endif disabled>
                                        <label class="form-check-label" for="inlineCheckbox3">Laravel</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        @endsection
