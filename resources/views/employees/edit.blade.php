@extends('layouts.master')

@section('title', 'Employees')

@section('content')

    <div class=" my-3 row align-items-center">
        <div class="col-md-6">
            <h4 class="text-dark my-4 me-auto"><i class="material-icons"
                    style="vertical-align: -4px">person</i>{{ __('messages.EditEmployeeEdit') }}
            </h4>
        </div>
    </div>

    <div class="shadow p-3 row mb-5 bg-white rounded" style="background: #e8ebe8">

        <div class="col-md-10 offset-md-2">

            @if (Session::has('updateFailMessage'))
                <div class=" text-center alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('updateFailMessage') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="col-md-6">
                <img class="img-account-profile rounded-circle mb-2" id="preview"
                    src="{{ Storage::url('employees/photos/' . $employee->photo) }}" alt="Preview">
            </div>

            <form class="row g-5" action="{{ route('employees.update', $employee->id) }}" method="POST"
                enctype="multipart/form-data">

                @csrf
                @method('patch')

                <div class="col-md-5">
                    <label id="photolabel" for="photo"
                        class="form-label d-inline-block">{{ __('messages.CreatePhoto') }}<span
                            class="required">*</span></label>
                    <input type="file" class="form-control @if ($errors->has('photo')) is-invalid @endif"
                        id="photo" value="{{ Storage::url($employee->photo) }}" name="photo">

                    @error('photo')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('photo') }}</div>
                    @enderror
                </div>

                <div class="col-md-5"></div>

                <div class="col-md-5">
                    <label for="id" class="form-label">{{ __('messages.CreateId') }}<span
                            class="required">*</span></label>
                    <input type="text" class="form-control @if ($errors->has('id')) is-invalid @endif"
                        id="id" name="id" value="{{ $employee->employee_id }}" disabled>
                    @error('id')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('id') }}</div>
                    @enderror
                </div>


                <div class="col-md-5">
                    <label for="name" class="form-label">{{ __('messages.CreateName') }}<span
                            class="required">*</span></label>

                    <input type="text" class="form-control @if ($errors->has('name')) is-invalid @endif"
                        id="name" maxlength="25" name="name" value="{{ old('name', $employee->name) }}">
                    @error('name')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('name') }}</div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label for="email" class="form-label">{{ __('messages.CreateEmail') }}<span
                            class="required">*</span></label>
                    <input type="email" class="form-control @if ($errors->has('email')) is-invalid @endif"
                        id="email" maxlength="40" name="email" value="{{ old('email', $employee->email) }}">
                    @error('email')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('email') }}</div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label for="phone" class="form-label">{{ __('messages.CreatePhone') }}<span
                            class="required">*</span></label>
                    <input type="text" class="form-control @if ($errors->has('phone')) is-invalid @endif"
                        id="phone" maxlength="14" name="phone" value="{{ old('phone', $employee->phone) }}">
                    @error('phone')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('phone') }}</div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label for="dateOfBirth" class="form-label">{{ __('messages.CreateDateOfBirth') }}<span
                            class="required">*</span></label>
                    <input type="date" class="form-control @if ($errors->has('dateOfBirth')) is-invalid @endif"
                        id="date" name="dateOfBirth" value="{{ old('dateOfBirth', $employee->dateOfBirth) }}">
                    @error('dateOfBirth')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('dateOfBirth') }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label for="nrc" class="form-label">{{ __('messages.CreateNRC') }}<span
                            class="required">*</span></label>
                    <input type="text" class="form-control @if ($errors->has('nrc')) is-invalid @endif"
                        id="nrc" name="nrc" value="{{ old('nrc', $employee->nrc) }}">
                    @error('nrc')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('nrc') }}</div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label> {{ __('messages.CreateGender') }} </label><span class="required">*</span>

                    <input class="form-check-input ms-4 @if ($errors->has('gender')) is-invalid @endif"
                        type="radio" name="gender" id="male" value="male"
                        @if (old('gender', $employee->gender) == 'male') checked @endif>
                    <label class="form-check-label " style="font-weight: 360"
                        for="male">{{ __('messages.CreateGenderMale') }}</label>

                    <input class="form-check-input ms-4 @if ($errors->has('gender')) is-invalid @endif ms-3"
                        type="radio" name="gender" id="female" value="female"
                        @if (old('gender', $employee->gender) == 'female') checked @endif>
                    <label class="form-check-label" style="font-weight: 360"
                        for="female">{{ __('messages.CreateGenderFemale') }}</label>
                    @error('gender')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('gender') }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-5">

                    @php
                        $languages = explode(', ', $employee->languages);
                    @endphp

                    <label> {{ __('messages.CreateLanguage') }} </label><span class="required">*</span>

                    <input class="form-check-input ms-4 @if ($errors->has('language')) is-invalid @endif"
                        type="checkbox" id="english" name="language[]" value="English"
                        @if (in_array('English', old('language', $languages))) checked @endif>
                    <label class="form-check-label" style="font-weight: 360"
                        for="english">{{ __('messages.CreateLanguageEnglish') }}</label>

                    <input class="form-check-input ms-4 @if ($errors->has('language')) is-invalid @endif"
                        type="checkbox" id="japan" name="language[]" value="Japan"
                        @if (in_array('Japan', old('language', $languages))) checked @endif>
                    <label class="form-check-label" style="font-weight: 360"
                        for="japan">{{ __('messages.CreateLanguageJapan') }}</label>

                    @error('language')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('language') }}
                        </div>
                    @enderror
                </div>


                <div class="col-md-10">
                    <label for="address" class="form-label">{{ __('messages.CreateAddress') }}<span
                            class="required">*</span></label>
                    <textarea class="form-control @if ($errors->has('address')) is-invalid @endif" id="address" name="address"
                        rows="2" maxlength="255" spellcheck="false">{{ old('address', $employee->address) }}</textarea>
                    @error('address')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('address') }}
                        </div>
                    @enderror
                </div>

                <div class="col-md-5">
                    <label> {{ __('messages.CreateCareer') }} </label><span class="required">*</span>

                    <select class="form-select @if ($errors->has('career')) is-invalid @endif" name="career">
                        <option value="Frontend" @if (old('career', $employee->career) == 'Frontend') selected @endif>
                            Frontend
                        </option>
                        <option value="Backend" @if (old('career', $employee->career) == 'Backend') selected @endif>
                            Backend
                        </option>
                        <option value="Fullstack" @if (old('career', $employee->career) == 'Fullstack') selected @endif>
                            Fullstack
                        </option>
                        <option value="Mobile" @if (old('career', $employee->career) == 'Mobile') selected @endif>
                            Mobile
                        </option>
                    </select>
                    @error('career')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('career') }}
                        </div>
                    @enderror
                </div>


                <div class="col-md-5">
                    <label> {{ __('messages.CreateLevel') }} </label><span class="required">*</span>

                    <select class="form-select @if ($errors->has('level')) is-invalid @endif" name="level">
                        <option value="Beginner" @if (old('level', $employee->level) == 'Beginner') selected @endif>
                            Beginner
                        </option>
                        <option value="Junior Engineer" @if (old('level', $employee->level) == 'Junior Engineer') selected @endif>Junior
                            Engineer
                        </option>
                        <option value="Engineer" @if (old('level', $employee->level) == 'Engineer') selected @endif>
                            Engineer
                        </option>
                        <option value="Senior Engineer" @if (old('level', $employee->level) == 'Senior Engineer') selected @endif>
                            Senior Enginner
                        </option>
                    </select>
                    @error('level')
                        <div id="employee_idHelp" class="form-text text-danger">{{ $errors->first('level') }}
                        </div>
                    @enderror

                </div>

                @php
                    $programming_languages = explode(', ', $employee->programming_languages);
                @endphp

                <div class="col-md-10">
                    <label>{{ __('messages.CreateProgramingLanguage') }}</label><span class="required">*</span>

                    <div class="prog_lang_container my-3">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="Android" name="prog_lang[]" value="Android"
                                @if (in_array('Android', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="Android">Android</label>
                        </div>

                        <div class="form-check form-check-inline ms-2">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="Java" name="prog_lang[]" value="Java"
                                @if (in_array('Java', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="Java">Java</label>
                        </div>

                        <div class="form-check form-check-inline ms-2">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="PHP" name="prog_lang[]" value="PHP"
                                @if (in_array('PHP', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="PHP">PHP</label>
                        </div>

                        <div class="form-check form-check-inline ms-3">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="React" name="prog_lang[]" value="React"
                                @if (in_array('React', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="React">React</label>
                        </div>

                        <div class="form-check form-check-inline">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="Laravel" name="prog_lang[]" value="Laravel"
                                @if (in_array('Laravel', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="Laravel">Laravel</label>
                        </div>

                        <div class="form-check form-check-inline ms-3">
                            <input class="form-check-input @if ($errors->has('prog_lang')) is-invalid @endif"
                                type="checkbox" id="C++" name="prog_lang[]" value="C++"
                                @if (in_array('C++', old('prog_lang', $programming_languages))) checked @endif>
                            <label class="form-check-label" style="font-weight: 360" for="C++">C++</label>
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
