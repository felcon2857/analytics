analytics_app.controller(
  "home_ctrl",
  function ($scope, $state, $filter, $q, db_model) {
    // filters doctors range
    var date = new Date();
    date.setDate(date.getDate() - 7);
    $scope.date_from = date;
    $scope.date_to = new Date();
    $scope.from_age = 0;
    $scope.to_age = 0;
    $scope.sex = "";
    $scope.status = "";
    $scope.doctors = 0;
    $scope.fromDate = $filter("date")($scope.date_from, "yyyy-MM-dd");
    $scope.toDate = $filter("date")($scope.date_to, "yyyy-MM-dd");
    $scope.itemsPerPage = 10;
    $scope.onload = false;

    // graph vairable
    $scope.series = ["Age", "Sex", "Status", "Doctors"];
    $scope.options = {
      // legend: { display: true },
      // scales: {
      //   xAxis: { grid: { display: false } },
      //   yAxis: {
      //     grid: { borderDash: [3, 5] },
      //     id: "y-axis-1",
      //     ticks: {
      //       suggestedMin: 10,
      //       suggestedMax: 90,
      //     },
      //   },
      // },
      elements: { arc: { borderWidth: 0 } },
    };
    $scope.datasetOverride = [
      {
        borderWidth: 1,
      },
    ];
    $scope.colours = [
      { backgroundColor: "#E74C3C", borderColor: "#E74C3C" },
      { backgroundColor: "#9B59B6", borderColor: "#9B59B6" },
      { backgroundColor: "#3498DB", borderColor: "#3498DB" },
      { backgroundColor: "#1ABB9C", borderColor: "#1ABB9C" },
    ];

    $scope.data = [
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
      [0, 0, 0, 0, 0, 0, 0],
    ];
    // scope.date = [age, sex, status, doctors]
    $scope.applyFilters = function () {
      let date_range = $scope.getDatesInRange($scope.date_from, $scope.date_to);
      var sex = $scope.sex == "" ? "" : $scope.sex;
      var status = $scope.status == "" ? "" : $scope.status;
      var fromDate = $filter("date")($scope.date_from, "yyyy-MM-dd");
      var toDate = $filter("date")($scope.date_to, "yyyy-MM-dd");

      $scope.labels = date_range;
      $scope.data[0] = new Array();
      $scope.data[1] = new Array();
      $scope.data[2] = new Array();
      $scope.data[3] = new Array();
      $scope.onload = true;

      date_range.forEach((element, index) => {
        $scope.onload = false;
        $scope
          .countByAge(element, $scope.from_age, $scope.to_age)
          .then(function (age) {
            $scope.data[0].push(age.clinix_count);
          });
        $scope.countSex(element, sex).then(function (sex) {
          $scope.data[1].push(sex.clinix_count);
        });
        $scope.getCountByStatus(element, status).then(function (status) {
          $scope.data[2].push(status.clinix_count);
        });
        $scope
          .getCountByDoctors(element, $scope.doctors)
          .then(function (doc_id) {
            $scope.data[3].push(doc_id.clinix_count);
          });
      });

      // patient lists
      $scope.getPatientList(
        fromDate,
        toDate,
        sex,
        status,
        $scope.from_age,
        $scope.to_age,
        $scope.doctors
      );
    };
    // count by age
    $scope.countByAge = function (date, agefrom, ageto) {
      return db_model
        .fac_getCountByAge(date, agefrom, ageto)
        .then(function (res) {
          return res.data[0];
        });
    };
    // count by sex
    $scope.countSex = function (date, sex) {
      return db_model.fac_getCountSex(date, sex).then(function (res) {
        return res.data[0];
      });
    };
    // count status
    $scope.getCountByStatus = function (date, status) {
      return db_model.fac_getCountByStatus(date, status).then(function (res) {
        return res.data[0];
      });
    };
    // count doctors
    $scope.getCountByDoctors = function (date, doc_id) {
      return db_model.fac_getCountByDoctors(date, doc_id).then(function (res) {
        return res.data[0];
      });
    };
    // get doctor list
    $scope.getDoctorList = function () {
      db_model.fac_getDoctorList().then(function (res) {
        $scope.dxObj = res.data;
      });
    };
    $scope.getDoctorList();

    // get patient list by filter
    $scope.getPatientList = function (
      dateFrom,
      dateTo,
      sex,
      status,
      agefrom,
      ageto,
      doc_id
    ) {
      db_model
        .fac_getPatientList(
          dateFrom,
          dateTo,
          sex,
          status,
          agefrom,
          ageto,
          doc_id
        )
        .then(function (res) {
          $scope.pxObj = res.data;
          let date_range = $scope.getDatesInRange(
            $scope.date_from,
            $scope.date_to
          );
          $scope.labels = date_range;
          /** const dateOut = res.data.map(function (index) {
            return index.filter(function (element) {
              return element.appt_date == $scope.labels;
            }).length;
          });
          console.log(dateOut);
          */
        });
    };
    $scope.getPatientList(
      $scope.fromDate,
      $scope.toDate,
      "",
      "",
      $scope.from_age,
      $scope.to_age,
      $scope.doctors
    );

    // filtered date range
    $scope.getDatesInRange = function (startDate, endDate) {
      const dateFrom = new Date(startDate.getTime());

      const dates = [];

      while (dateFrom <= endDate) {
        dates.push($filter("date")(new Date(dateFrom), "yyyy-MM-dd"));
        dateFrom.setDate(dateFrom.getDate() + 1);
      }
      return dates;
    };
  }
);
