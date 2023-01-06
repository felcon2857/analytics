<div class="container-fluid vh-100 bg-theme px-3 py-4">
    <div class="bg-white shadow-sm rounded px-3 py-5">
        <div class="row align-items-center px-0 ">
            <div class="col-lg-5">
                <div class="d-flex align-items-center justify-content-center">
                    <div class="frm-box w-100">
                        <span>From Date</span>
                        <input type="date" ng-model="date_from">
                    </div>
                    <div class="frm-box w-100 mx-3">
                        <span>To Date</span>
                        <input type="date" ng-model="date_to">
                    </div>
                    <button class="btn-theme btn-theme-green w-50 mr-2" ng-click="filter_docs()">
                        <i class="fa-solid fa-filter"></i>
                        &nbsp;Filter
                    </button>
                    <button class="btn-theme w-50">
                        <i class="fa-solid fa-filter-circle-xmark"></i>
                        &nbsp;Cancel
                    </button>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="d-flex align-items-center justify-content-end">
                    <!-- <div class="search-box w-25 mr-3">
                        <i class="fa-solid fa-magnifying-glass mr-3"></i>
                        <input type="text" placeholder="filter & add doctors">
                    </div> -->
                    <button class="btn-theme btn-theme-green" ng-click="showFilterBox()">
                        <i class="fa-solid fa-user-doctor"></i>
                        &nbsp;Filter Doctors
                    </button>
                </div>
            </div>
        </div>
        <!-- <hr> -->
        <div class="row pt-5">
            <div class="col-lg-12">
                <h3 class="text-center font-weight-bold text-dark">NUMBER OF PATIENTS</h3>
                <h4 class="text-center text-dark">From {{date_from | date:'MMM dd, yyyy'}} - {{date_to | date:'MMM dd, yyyy'}}</h4>
                <canvas id="bar" height="100" class="chart chart-bar" chart-colors="colours" chart-options="options" chart-data="data" chart-labels="labels" chart-series="series">
                </canvas>
            </div>
        </div>
    </div>
</div>