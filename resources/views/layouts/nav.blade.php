<div class=" nav container-fluid">
    <nav class=" nav container navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="nav-link active text-white" style="font-size: 19px" href="{{ route('employees.index') }}">
                <i class="material-icons home">home</i>{{__("messages.navHome")}}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    
                    <li class="nav-item dropdown">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle nav-btn lang_button" type="button" id="languageDropdown" style="color: white"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Language
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                <li><a class="dropdown-item" href="{{ route('set.language', 'en') }}">English</a></li>
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="{{ route('set.language', 'my') }}">မြန်မာ</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white me-3" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{__("messages.navEmployees")}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('employees.projects') }}">{{__("messages.navProjectAssign")}}</a>
                            </li>
                            <div class="dropdown-divider"></div>
                            <li>
                                <a class="dropdown-item" href="{{ route('employees.index') }}">{{__("messages.navEmployeeList")}}</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active text-white" aria-current="page"
                            href="{{ route('admins.logout') }}">{{__("messages.navLogout")}}</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</div>
