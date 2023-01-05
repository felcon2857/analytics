analytics_app.controller(
  "diagram_ctrl",
  function ($scope, $state, db_model, $uibModal) {
    var date = new Date();
    date.setDate(date.getDate() - 7);
    $scope.date_from = date;
    $scope.date_to = new Date();
    $scope.data = [0];
    $scope.labels = ["Number of Patients"];

    localStorage.setItem("labels", JSON.stringify($scope.labels));

    $scope.check_doc = "";
    $scope.colours = ["#169f85", "#169f85", "#169f85", "#169f85"];

    $scope.apply_filters = function () {};
    $scope.getDoctorList = function () {
      db_model.fac_getDoctorList().then(function (res) {
        $scope.dxObj = res.data;
        var labels = [];
        for (var i = 0; i < res.data.length; i++) {
          $scope.dr = "DR. " + res.data[i].doc;
          labels = JSON.parse(localStorage.getItem("labels"));
          labels.unshift($scope.dr);
          localStorage.setItem("labels", JSON.stringify(labels));

          db_model
            .fac_countPxByDoctor("2021-01-01", "2022-12-31", res.data[i].px_id)
            .then(function (dx) {
              $scope.data = [89, 47, 65, 201];
              //   $scope.data.unshift(dx.data[0].clinix_count);
            });
        }
        $scope.labels = labels;
      });
    };
    $scope.getDoctorList();

    $scope.insert_checked = function (dxObj) {
      var arr = [];
      for (var i in dxObj) {
        if (dxObj[i].doc == "Y") {
          arr.push(dxObj[i].doc);
        }
      }
      console.log(dxObj[i].doc);
    };
    $scope.showFilterBox = function () {
      var $uibModalInstance = $uibModal.open({
        animation: true,
        ariaLabelledBy: "modal-title",
        ariaDescribedBy: "modal-body",
        templateUrl: "templates/modal/doc.modal.php",
        backdrop: "static",
        scope: $scope,
      });

      $scope.closeFilterBox = function () {
        $uibModalInstance.close();
      };
    };
  }
);
