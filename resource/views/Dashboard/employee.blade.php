<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card mini-stat bg-primary text-white">
            <div class="card-body">
                <div class="mb-4">
                    <div class="float-left mini-stat-img mr-4">
                        <i class="ti-alarm-clock" style="font-size: 20px"></i>
                    </div>
                    <h6 class="font-16 text-uppercase mt-0 text-white-50">On Time <br> Percentage for {{ date('F') }}
                    </h6>
                    <h4 class="font-500">{{ $percentEmployee ?? 0 }} %<i class="text-danger ml-2"></i></h4>
                    <span class="peity-donut"
                        data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72"
                        data-height="72">{{ $ontimeEmployee ?? 0 }}/31</span>

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
                    <h4 class="font-500"> {{ $ontimeEmployee ?? 0 }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut"
                        data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72"
                        data-height="72">{{ $ontimeEmployee ?? 0 }}/31</span>

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
                    <h4 class="font-500">{{ $lateEmployee ?? 0 }}<i class=" text-success ml-2"></i></h4>
                    <span class="peity-donut"
                        data-peity='{ "fill": ["#02a499", "#f2f2f2"], "innerRadius": 28, "radius": 32 }' data-width="72"
                        data-height="72">{{ $lateEmployee ?? 0 }}/31</span>

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
