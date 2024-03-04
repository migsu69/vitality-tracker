@extends('layouts.app')

@section('content')

    <!-- Alert Modal -->
    <div class="text-center">
        <div class="row justify-content-center">
            <div class="col-4 text-center">
                @if(session('error'))
                    <div id="myAlert" class="alert alert-danger d-flex align-items-center justify-content-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div class="col-8">
                            {{ session('error') }}
                        </div>
                    </div>
                @elseif (session('success'))
                <div id="myAlert" class="alert alert-success d-flex align-items-center justify-content-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div class="col-8">
                        {{ session('success') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <!-- Alert Modal End-->

    <div class="container">
        <div class="row my-5 g-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card bg-dark bg-opacity-25">
                    <div class="card-body m-xl-4">
                        <div class="text-center">
                            <img src="{{ asset('img/logo.png') }}" class="logo">
                            <h2 class="fw-bold text-danger">MABALACAT CITY COLLEGE</h2>
                        </div>
                        <hr>
                        <button type="button" class="btn btn-danger w-100 m-1" data-bs-toggle="modal" data-bs-target="#form-modal" {{ $vitalsExist && $vitalExistLastMonth ? 'disabled' : '' }}>
                            <i class="fa-solid fa-circle-plus me-2"></i>
                            Add New Record
                        </button>
                        <form action="{{ route('logout') }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-secondary w-100 m-1">
                                <i class="fa-solid fa-arrow-right-from-bracket me-2"></i>
                                Logout
                            </button>
                        </form>
                        <hr>
                        <!-- Filter Year -->
                        <div class="w-100">
                            <form action="{{ route('dashboard', ['employee_id' => $encryptedEmployeeId]) }}" method="GET">
                                @csrf
                                <label for="show-records" class="form-label">Show Records</label>
                                <select class="form-select form-select-lg mb-3" id="show-records" name="selectedYear">
                                    <option>Please Select Year</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}" {{ request('selectedYear') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fa-solid fa-filter me-2"></i>
                                    Filter Vitality Records
                                </button>
                            </form>
                            <br>
                            @if($wellnessCoordinator)
                                <form action="{{ route('export', ['employee_id' => $encryptedEmployeeId]) }}" method="GET">
                                    @csrf
                                    <input type="hidden" name="selectedYear" value="{{ request('selectedYear') }}">
                                    <button type="submit" class="btn btn-danger w-100" {{ request('selectedYear') == "Please Select Year" ? 'disabled' : '' }}>
                                        Export Records
                                    </button>
                                </form>
                            @endif
                        </div>                        
                        <!-- Filter Year End-->

                        <hr>
                        <div class="text-center">
                            <h4 class="fw-bold text-danger">Hello, {{$employee->first_name}} {{$employee->last_name}}</h4>
                        </div>
                    </div>
                  </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-9">
                <div class="row row-cols-1 row-cols-xl-3 g-4">
                    @if($vitals->count() > 0)
                        @foreach($vitals->sortBy(function ($vital) {
                            return $vital->month;
                        }) as $vital)
                            <div class="col">
                                <div class="card h-100 {{ $vital->month == now()->format('m') && $vital->year == now()->year ? 'border-danger' : '' }}">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                            <a class="text-muted dropdown-toggle font-size-16" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                <i class="fa-solid fa-ellipsis"></i>
                                            </a>

                                            <!-- Drop Down For Edit and Remove-->
                                            <div class="dropdown-menu dropdown-menu-end">
                                                @foreach (range(1,2) as $months)
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $vital->id }}"
                                                        {{ $vital->month > str_pad($months, 2, '0', STR_PAD_LEFT) && $vital->year == now()->year ? '' : 'style=display:none;' }}>
                                                        Edit
                                                    </a>
                                                @endforeach
                                                 <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#remove-modal-{{ $vital->id }}">Remove</a>
                                            </div>
                                            <!-- Drop Down For Edit and Remove End-->

                                            <!-- Remove Vitals Modal-->
                                            <div class="modal fade" id="remove-modal-{{ $vital->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                                  <div class="modal-content">
                                                    <div class="modal-header bg-danger">
                                                      <h1 class="modal-title fs-5 text-white fw-bold" id="exampleModalLabel">Remove Record</h1>
                                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <h3>Are you sure?</h3>
                                                        <p>Do you really want to delete this item? This process cannot be undone.</p>
                                                    </div>
                                                    <div class="modal-footer">
                                                        @if(isset($vital))
                                                            <form action="{{ route('vitals.destroy', ['vital' => $vital->id]) }}" method="post">
                                                                @csrf
                                                                @method('DELETE')

                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    <i class="fa-solid fa-circle-xmark me-2"></i>Close
                                                                </button>

                                                                <button type="submit" class="btn btn-danger">
                                                                    <i class="fa-solid fa-trash me-2"></i>Confirm Action
                                                                </button>
                                                            </form>
                                                        @else
                                                            <p>No vital record found.</p>
                                                        @endif
                                                    </div>
                                                  </div>
                                                </div>
                                            </div>
                                            <!-- Remove Vitals Modal End-->

                                            <!-- Edit Vitals Modal-->
                                            <div class="modal fade" id="edit-modal-{{ $vital->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger">
                                                          <h1 class="modal-title fs-5 text-white fw-bold" id="exampleModalLabel">Edit Record for <b>{{ \Carbon\Carbon::createFromFormat('m', $vital->month)->format('F') }}, {{ $vital->year }}</b></h1>
                                                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('vitals.update', ['vital' => $vital->id]) }}" method="post">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-floating mb-3">
                                                                    <input type="number" inputmode="numeric" class="form-control" name="pulse_rate" value="{{ $vital->pulse_rate }}" oninput="limitInputLength(this, 4)" placeholder="Pulse Rate" required>
                                                                    <label for="pulse-rate">Pulse Rate</label>
                                                                </div>

                                                                <div class="form-floating mb-3">
                                                                    <input type="number" inputmode="numeric" class="form-control" name="body_temperature" value="{{ $vital->body_temperature }}" oninput="limitInputLength(this, 3)" placeholder="Body Temperature" required>
                                                                    <label for="body-temperature">Body Temperature (in degree celsius)</label>
                                                                </div>

                                                                <div class="form-floating mb-3">
                                                                    <input type="number" inputmode="numeric" class="form-control" name="respiratory_rate" value="{{ $vital->respiratory_rate }}" oninput="limitInputLength(this, 4)" placeholder="Respiratory Rate" required>
                                                                    <label for="respiratory-rate">Respiratory Rate</label>
                                                                </div>

                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control" name="bp" value="{{ $vital->bp }}" placeholder="Blood Pressure" required>
                                                                    <label for="blood-pressure">Blood Pressure</label>
                                                                </div>

                                                                <div class="form-floating mb-3">
                                                                    <input type="text" class="form-control" name="bmi" value="{{ $vital->bmi }}" placeholder="Body Mass Index" oninput="limitInputLength(this, 3)" required>
                                                                    <label for="body-mass-index">Body Mass Index</label>
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-circle-xmark me-2"></i>Close</button>
                                                            <button type="submit" class="btn btn-danger"><i class="fa-solid fa-floppy-disk me-2"></i>Save changes</button>
                                                            </div>
                                                        </form>
                                                      </div>
                                                </div>
                                            </div>
                                            <!-- Edit Vitals Modal End-->
                                        </div>                                      
                                        <!-- View Records -->
                                        <h5 class="card-title fw-bold text-danger">
                                            {{ \Carbon\Carbon::createFromFormat('m', $vital->month)->format('F') }}
                                        </h5>                                        
                                        <p class="card-text">
                                            <ul class="list-unstyled ms-2">
                                                <li>
                                                    <i class="fa-solid fa-fw fa-bed-pulse me-2"></i>
                                                    <span class="text-muted">Pulse Rate: </span>
                                                    <span class="badge rounded-pill text-bg-danger float-end">{{ $vital->pulse_rate }} BPM</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-fw fa-temperature-three-quarters me-2"></i>
                                                    <span class="text-muted">Body Temperature: </span>
                                                    <span class="badge rounded-pill text-bg-danger float-end">{{ $vital->body_temperature }} °C</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-fw fa-heart-pulse me-2"></i>
                                                    <span class="text-muted">Respiratory Rate: </span>
                                                    <span class="badge rounded-pill text-bg-danger float-end">{{ $vital->respiratory_rate }}  BPM</span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-fw fa-droplet me-2"></i>
                                                    <span class="text-muted">Blood Pressure: </span>
                                                    <span class="badge rounded-pill text-bg-danger float-end">{{ $vital->bp }} </span>
                                                </li>
                                                <li>
                                                    <i class="fa-solid fa-fw fa-person me-2"></i>
                                                    <span class="text-muted">Body Mass Index: </span>
                                                    <span class="badge rounded-pill text-bg-danger float-end">{{ $vital->bmi}} </span>
                                                </li>
                                            </ul>
                                        </p>
                                        <!-- View Records End -->
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p>No vitals recorded yet.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
    <!-- Create Vitals Modal-->
    <div class="modal fade" id="form-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h1 class="modal-title fs-5 text-white fw-bold" id="exampleModalLabel">Add New Record</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('vitals.store', ['employee_id' => $employee->id]) }}" method="post">
                        @csrf
                        <select class="form-select form-select-lg mb-3" id="show-records" name="month">
                            <option disabled selected>Select Month</option>
                            @foreach ($monthsWithNoRecords as $month)
                                <option value="{{ str_pad($month, 2, '0', STR_PAD_LEFT) }}">
                                    {{ date("F", mktime(0, 0, 0, $month, 1)) }}
                                </option>
                            @endforeach
                        </select>                                                                                                                                                                                                                   
                        <!-- Pulse Rate Input -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="pulse-rate" name="pulse_rate" oninput="limitInputLength(this, 4)" placeholder="Pulse Rate" required>
                            <label for="pulse-rate">Pulse Rate</label>
                        </div>
                        <!-- Body Temperature Input -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="body-temperature" name="body_temperature" oninput="limitInputLength(this, 3)" placeholder="Body Temperature" required>
                            <label for="body-temperature">Body Temperature (in degree Celsius)</label>
                        </div>
                        <!-- Respiratory Rate Input -->
                        <div class="form-floating mb-3">
                            <input type="number" class="form-control" id="respiratory-rate" name="respiratory_rate" oninput="limitInputLength(this, 4)" placeholder="Respiratory Rate" required>
                            <label for="respiratory-rate">Respiratory Rate</label>
                        </div>
                        <!-- Blood Pressure Input -->
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="blood-pressure" name="bp" placeholder="Blood Pressure" required>
                            <label for="blood-pressure">Blood Pressure</label>
                        </div>
                        <!-- BMI Input -->
                        <div class="form-floating mb-3">
                            <input type="text" id="bmiInput" name="bmi" class="form-control" oninput="limitInputLength(this, 3)" placeholder="Body Mass Index" required>
                            <label for="bmiInput">Body Mass Index</label>
                        </div>
                        <!-- Buttons -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa-solid fa-circle-xmark me-2"></i>Close
                            </button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fa-solid fa-floppy-disk me-2"></i>Save changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Create Vitals Modal End-->
@endsection
    
