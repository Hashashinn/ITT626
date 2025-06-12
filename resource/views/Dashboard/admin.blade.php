<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <span class="ti-id-badge" style="font-size: 20px"></span>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Total <br> Employees</h5>
                    <h4 class="font-500">{{ $allEmployee ?? 0 }} </h4>
                    <span class="ti-user" style="font-size: 71px"></span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/employees" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>
                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alarm-clock" style="font-size: 20px"></i>
                    </div>
                    <h6 class="font-16 text-uppercase mt-0 text-white-50">On Time <br> Percentage for
                        {{ date('F') }}</h6>
                    <h4 class="font-500">{{ $percentAdmin ?? 0 }} %<i class="text-danger ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $ontimeAdmin ?? 0 }}/31</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/attendance" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class=" ti-check-box " style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">On Time for <br> {{ date('F') }}</h5>
                    <h4 class="font-500"> {{ $ontimeAdmin ?? 0 }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $ontimeAdmin ?? 0 }}/31</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/attendance" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alert" style="font-size: 20px"></i>
                    </div>
                    <h5 class="font-16 text-uppercase mt-0 text-white-50">Late for <br> {{ date('F') }}</h5>
                    <h4 class="font-500">{{ $lateAdmin ?? 0 }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut" data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72" data-height="72">{{ $lateAdmin ?? 0 }}/31</span>

                </div>
                <div class="pt-2">
                    <div class="float-right">
                        <a href="/attendance" class="text-white-50"><i class="mdi mdi-arrow-right h5"></i></a>
                    </div>

                    <p class="text-white-50 mb-0">More info</p>
                </div>
            </div>
        </div>
    </div>
</div>

@if (Auth::user()->role->role_id == 2)
    <div class="row">
        <div class="col-md-12">
            <div class="card recent-activities-card">
                <div class="card-body">
                    <h5 class="card-title">Recent Activities</h5>
                    <div class="table-responsive">
                        <table id="recentActivitiesTable" class="table table-bordered recent-activities-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Description</th>
                                    <th>User</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($logActions as $index => $log)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $log->desc ?? '-' }}</td>
                                        <td>{{ $log->users->name ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#recentActivitiesTable').DataTable({
            "order": [[0, "desc"]]
        });
    });
</script>
