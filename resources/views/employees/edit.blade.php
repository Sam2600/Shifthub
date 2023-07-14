@extends('layouts.master')

@section('title', 'Employees')

@section('content')

    <div>
        <h4 class="text-dark my-4 me-auto"><i class="material-icons"
                style="vertical-align: -4px">person</i>{{ __('messages.EditEmployeeEdit') }}
        </h4>
    </div>

    <div class="shadow p-3 mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="row">

            <div class="col-md-8 offset-md-2">

                <div class="col-md-8 offset-md-2">

                    @if (Session::has('updateFailMessage'))
                        <div class=" text-center alert alert-danger alert-dismissible fade show" role="alert">
                            {{ Session::get('updateFailMessage') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (Session::has('wrongAdmin1'))
                        <div class=" text-center alert alert-warning alert-dismissible fade show" role="alert">
                            {{ Session::get('wrongAdmin1') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    

                    {{-- <p>{{$page}}</p> --}}


                    <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('patch')

                        <img id="preview" src="{{ Storage::url('employees/photos/' . $employee->photo) }}" alt="Preview">
                        <div class="d-flex align-items-baseline mb-4 my-2">


                            <label id="photolabel" for="photo"
                                class="form-label d-inline-block">{{ __('messages.CreatePhoto') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="file"
                                    class="form-control @if ($errors->has('photo')) is-invalid @endif" id="photo"
                                    value="{{ Storage::url($employee->photo) }}" name="photo">

                                @error('photo')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('photo') }}</div>
                                @enderror
                            </div>

                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="id" class="form-label">{{ __('messages.CreateId') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control @if ($errors->has('id')) is-invalid @endif" id="id"
                                    name="id" value="{{ $employee->employee_id }}" disabled>
                                @error('id')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('id') }}</div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-4">
                            <label for="name" class="form-label">{{ __('messages.CreateName') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control @if ($errors->has('name')) is-invalid @endif" id="name" maxlength="25"
                                    name="name" value="{{ old('name', $employee->name) }}">
                                @error('name')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('name') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="email" class="form-label">{{ __('messages.CreateEmail') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="email"
                                    class="form-control @if ($errors->has('email')) is-invalid @endif" id="email" maxlength="40"
                                    name="email" value="{{ old('email', $employee->email) }}">
                                @error('email')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('email') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="phone" class="form-label">{{ __('messages.CreatePhone') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control @if ($errors->has('phone')) is-invalid @endif" id="phone" maxlength="14"
                                    name="phone" value="{{ old('phone', $employee->phone) }}">
                                @error('phone')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('phone') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="dateOfBirth" class="form-label">{{ __('messages.CreateDateOfBirth') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="date"
                                    class="form-control @if ($errors->has('dateOfBirth')) is-invalid @endif" id="date"
                                    name="dateOfBirth" value="{{ old('dateOfBirth', $employee->dateOfBirth) }}">
                                @error('dateOfBirth')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('dateOfBirth') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <label for="nrc" class="form-label">{{ __('messages.CreateNRC') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input type="text"
                                    class="form-control @if ($errors->has('nrc')) is-invalid @endif"
                                    id="nrc" name="nrc" value="{{ old('nrc', $employee->nrc) }}">
                                @error('nrc')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('nrc') }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-4">
                            <span><label> {{ __('messages.CreateGender') }} </label><span class="required">*</span></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <input class="form-check-input @if ($errors->has('gender')) is-invalid @endif"
                                    type="radio" name="gender" id="male" value="1"
                                    @if (old('gender', $employee->gender) == 1) checked @endif>
                                <label class="form-check-label " style="font-weight: 360"
                                    for="male">{{ __('messages.CreateGenderMale') }}</label>

                                <input class="form-check-input @if ($errors->has('gender')) is-invalid @endif ms-3"
                                    type="radio" name="gender" id="female" value="2"
                                    @if (old('gender', $employee->gender) == 2) checked @endif>
                                <label class="form-check-label" style="font-weight: 360"
                                    for="female">{{ __('messages.CreateGenderFemale') }}</label>
                                @error('gender')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('gender') }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex align-items-center my-3 mb-4">
                            <label for="address" class="form-label">{{ __('messages.CreateAddress') }}<span
                                    class="required">*</span></label>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <textarea class="form-control @if ($errors->has('address')) is-invalid @endif" id="address" name="address"
                                    rows="2" maxlength="255" spellcheck="false">{{ old('address', $employee->address) }}</textarea>
                                @error('address')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('address') }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{ __('messages.CreateLanguage') }} </label><span
                                    class="required">*</span></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @if ($errors->has('language')) is-invalid @endif"
                                        type="checkbox" id="english" name="language[]" value="1"
                                        @php $languages = explode(', ', $employee->language) @endphp
                                        @if (in_array(1, old('language', $languages))) checked @endif>
                                    <label class="form-check-label" style="font-weight: 360"
                                        for="english">{{ __('messages.CreateLanguageEnglish') }}</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input @if ($errors->has('language')) is-invalid @endif"
                                        type="checkbox" id="japan" name="language[]" value="2"
                                        @php $languages = explode(', ', $employee->language) @endphp
                                        @if (in_array(2, old('language', $languages))) checked @endif>
                                    <label class="form-check-label" style="font-weight: 360"
                                        for="japan">{{ __('messages.CreateLanguageJapan') }}</label>
                                </div>
                                @error('language')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('language') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{ __('messages.CreateCareer') }} </label><span class="required">*</span></span>

                            <div class="col-md-8 offset-md-2 ms-auto">
                                <select class="form-select @if ($errors->has('career')) is-invalid @endif"
                                    name="career">
                                    <option value="1" @if (old('career',$employee->career_id) == 1) selected @endif>FrontEnd
                                    </option>
                                    <option value="2" @if (old('career',$employee->career_id) == 2) selected @endif>BackEnd
                                    </option>
                                    <option value="3" @if (old('career',$employee->career_id) == 3) selected @endif>FullStack
                                    </option>
                                    <option value="4" @if (old('career',$employee->career_id) == 4) selected @endif>Mobile
                                    </option>
                                </select>
                                @error('career')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('career') }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label> {{ __('messages.CreateLevel') }} </label><span class="required">*</span></span>

                            <div class="col-md-8 offset-md-2 ms-auto">
                                <select class="form-select @if ($errors->has('level')) is-invalid @endif"
                                    name="level">
                                    <option value="1" @if (old('level',$employee->level_id) == 1) selected @endif>Beginner
                                    </option>
                                    <option value="2" @if (old('level',$employee->level_id) == 2) selected @endif>Junior
                                        Engineer</option>
                                    <option value="3" @if (old('level',$employee->level_id) == 3) selected @endif>Engineer
                                    </option>
                                    <option value="4" @if (old('level',$employee->level_id) == 4) selected @endif>Senior
                                        Enginner</option>
                                </select>
                                @error('level')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('level') }}
                                    </div>
                                @enderror
                            </div>
                        </div>


                        <div class="d-flex align-items-center mb-5">
                            <span><label>{{ __('messages.CreateProgramingLanguage') }}</label><span
                                    class="required">*</span></span>
                            <div class="col-md-8 offset-md-2 ms-auto">
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="1"
                                            @if (in_array(1, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox1">C++</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="2"
                                            @if (in_array(2, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox2">Java</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="3"
                                            @if (in_array(3, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox3">PHP</label>
                                    </div>
                                </div>


                                <div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="4"
                                            @if (in_array(4, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox3">React</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="5"
                                            @if (in_array(5, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox3">Android</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input
                                            class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                            type="checkbox" id="prog_lang" name="prog_lang[]" value="6"
                                            @if (in_array(6, old('prog_lang', $progs))) checked @endif>
                                        <label class="form-check-label" style="font-weight: 360"
                                            for="inlineCheckbox3">Laravel</label>
                                    </div>
                                </div>

                                @error('prog_lang')
                                    <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('prog_lang') }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <button type="submit"
                                class="btn btn-primary update">{{ __('messages.CreateUpdateButton') }}</button>
                            <a href="{{ url('employees/' . $employee->id . '/edit') }}"
                                class="btn btn-secondary reset ms-3">{{ __('messages.CreateResetButton') }}</a>
                        </div>
                    </form>

                </div>
            </div>

        @endsection

        <script>
            window.addEventListener('DOMContentLoaded', function() {
                var dateOfBirthInput = document.getElementById('dateOfBirth');
                var hasManuallySetDate = false;

                dateOfBirthInput.addEventListener('input', function() {
                    hasManuallySetDate = true;
                });

                dateOfBirthInput.addEventListener('focus', function() {
                    if (!hasManuallySetDate) {
                        var currentDate = new Date();
                        currentDate.setFullYear(2005);
                        this.valueAsDate = currentDate;
                    }
                });
            });


            document.addEventListener('DOMContentLoaded', function() {
                // Get the file input element
                var input = document.getElementById('photo');

                // Add an event listener for when a file is selected
                input.addEventListener('change', function(e) {
                    var file = e.target.files[0];
                    var reader = new FileReader();

                    // Set up the FileReader onload event
                    reader.onload = function(e) {
                        var previewImage = document.getElementById('preview');
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                    };

                    // Read the image file as a data URL
                    reader.readAsDataURL(file);
                });
            });
        </script>
