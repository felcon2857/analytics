<div class="d-flex vh-100">
    <?php include "sidebar.tpl.php" ?>
    <div class="container-fluid vh-100 bg-theme px-3 py-4">
        <div class="bg-white box-theme">
            <div class="row align-items-center px-5 pt-5 pb-3">
                <div class="col-lg-8 pr-2">
                    <div class="spinned-box" ng-show="onload == true">
                        <div class="spinner-border mb-3" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <span>Loading Data</span>
                    </div>

                    <div ng-show="onload == false">
                        <canvas id="bar" height="100" class="chart chart-bar" chart-colors="colours" chart-options="options" chart-data="data" chart-labels="labels" chart-series="series">
                        </canvas>
                    </div>

                </div>
                <div class="col-lg-4 pl-2">
                    <div class="frm-grp">
                        <h5 class="pb-4">
                            <i class="fa-solid fa-filters"></i>
                            Filtered Data By Doctors
                        </h5>
                        <!-- date filtered -->
                        <div class="row">
                            <div class="col-lg-6 pr-2">
                                <div class="frm-box mb-3">
                                    <span>From</span>
                                    <input type="date" ng-model="date_from">
                                </div>
                            </div>
                            <div class="col-lg-6 pl-2">
                                <div class="frm-box mb-3">
                                    <span>To</span>
                                    <input type="date" ng-model="date_to">
                                </div>
                            </div>
                        </div>
                        <!-- filter doctor -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="frm-box frm-box-select mb-3">
                                    <span>Doctor</span>
                                    <i class="fa-solid fa-user-doctor mr-2"></i>
                                    <select ng-model="doctors">
                                        <option ng-value="0">All Doctors</option>
                                        <option ng-repeat="dx in dxObj" ng-value="{{dx.px_id}}">
                                            {{dx.lastname +', '+ dx.firstname}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- age and sex filter -->
                        <div class="row">
                            <div class="col-lg-6 pr-2">
                                <div class="frm-box mb-3">
                                    <span>From age</span>
                                    <i class="fa-solid fa-sliders mr-2"></i>
                                    <input type="number" placeholder="To age" min="1" ng-model="from_age">
                                </div>
                            </div>
                            <div class="col-lg-6 pl-2">
                                <div class="frm-box mb-3">
                                    <span>To age</span>
                                    <i class="fa-solid fa-sliders mr-2"></i>
                                    <input type="number" placeholder="From age" min="1" ng-model="to_age">
                                </div>
                            </div>
                        </div>
                        <!-- status and doctor -->
                        <div class="row">
                            <div class="col-lg-6 pr-2">
                                <div class="frm-box frm-box-select mb-3">
                                    <span>Status</span>
                                    <i class="fa-solid fa-rings-wedding mr-2"></i>
                                    <select ng-model="status">
                                        <option value="">All Status</option>
                                        <option value="Single">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widowed">Widowed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 pl-2">
                                <div class="frm-box frm-box-select mb-3">
                                    <span>Sex</span>
                                    <i class="fa-solid fa-venus-mars mr-2"></i>
                                    <select ng-model="sex">
                                        <option value="">All Sex</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-end">
                        <button class="btn-theme mx-2">Cancel</button>
                        <button class="btn-theme btn-theme-green" ng-click="applyFilters()">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="pb-4">
                <div class="d-flex align-items-center justify-content-between mx-2">
                    <div class="search-box w-25">
                        <input type="text" placeholder="Search" ng-model="searchPx">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                    <select class="frm-cntrl" ng-model="itemsPerPage">
                        <option ng-value="10">10</option>
                        <option ng-value="20">20</option>
                        <option ng-value="50">50</option>
                        <option ng-value="100">010</option>
                    </select>
                </div>

                <div class="table-area px-2">
                    <table>
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="15%">Date</th>
                                <th width="30%">Doctors</th>
                                <th width="35%">Patients</th>
                                <th width="15%">Age / Sex / Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr ng-repeat="px in filterData = (pxObj | filter: searchPx) | limitTo:itemsPerPage:itemsPerPage*(currentPage-1) | orderBy  : '-appt_date'">
                                <td>{{$index+1}}</td>
                                <td>{{px.appt_date | date:'MMM dd, yyyy'}}</td>
                                <td>{{px.doctor}}</td>
                                <td>{{px.clinix_id}} - {{px.lastname +', '+ px.firstname}}</td>
                                <td>
                                    {{px.appt_age==0?'NA':px.appt_age}} / {{px.sex==''?'NA':px.sex}} /
                                    {{px.status_m==''?'NA':px.status_m}}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="d-flex align-items-center justify-content-center">
                    <ul uib-pagination total-items="filterData.length" num-pages="numPages" items-per-page="itemsPerPage" ng-model="currentPage" max-size="5" class="pagination" boundary-link-numbers="true" rotate="false"></ul>
                </div>
            </div>
        </div>
    </div>
</div>