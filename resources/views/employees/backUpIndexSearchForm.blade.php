<form action="{{ route('employees.index') }}" method="GET">
    <div class="input-group mb-3">


        <input type="text" name="employee_id" class="form-control" placeholder="Id" {{-- This is the process when the pure count of emps are 0 these form input field will be disabled --}}
            @if ($counts === 0) disabled @endif {{-- This process is when we got input session data (employee_id) we will remain selected  --}}
            @if (Session::has('employee_id')) value="{{ Session::get('employee_id') }}" @endif>


        <select class="form-select" name="career" @if ($counts === 0) disabled @endif>
            <option value="">Career</option>

            @foreach ($careers as $career)
                <option value="{{ $career }}" @if (Session::get('career') == $career) selected @endif>
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


        <select class="form-select" name="level" @if ($counts === 0) disabled @endif>
            <option value="">Level</option>

            @foreach ($levels as $level)
                <option value="{{ $level }}" @if (Session::get('level') == $level) selected @endif>
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

        <button type="submit" class=" search btn btn-outline-dark"
            @if ($counts === 0) disabled @endif>Search</button>
    </div>
</form>
