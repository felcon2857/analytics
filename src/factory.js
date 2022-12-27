analytics_app.factory("db_model", [
  "$http",
  function ($http) {
    var myIP = "127.0.0.1";
    var serviceBase = "api/";
    var obj = {};

    // age
    obj.fac_getCountByAge = function (date, agefrom, ageto) {
      return $http.get(
        serviceBase +
          "getCountByAge&date=" +
          date +
          "&agefrom=" +
          agefrom +
          "&ageto=" +
          ageto
      );
    };
    // sex
    obj.fac_getCountSex = function (date, sex) {
      return $http.get(
        serviceBase + "getCountSex&date=" + date + "&sex=" + sex
      );
    };
    // status
    obj.fac_getCountByStatus = function (date, status) {
      return $http.get(
        serviceBase + "getCountByStatus&date=" + date + "&status=" + status
      );
    };
    // doctor
    obj.fac_getCountByDoctors = function (date, doc_id) {
      return $http.get(
        serviceBase + "getCountByDoctors&date=" + date + "&doc_id=" + doc_id
      );
    };

    // doctor list
    obj.fac_getDoctorList = function () {
      return $http.get(serviceBase + "getDoctorList");
    };
    // get patient list by filters
    obj.fac_getPatientList = function (
      dateFrom,
      dateTo,
      sex,
      status,
      agefrom,
      ageto,
      doc_id
    ) {
      return $http.get(
        serviceBase +
          "getPatientList&dateFrom=" +
          dateFrom +
          "&dateTo=" +
          dateTo +
          "&sex=" +
          sex +
          "&status=" +
          status +
          "&doc_id=" +
          doc_id +
          "&agefrom=" +
          agefrom +
          "&ageto=" +
          ageto
      );
    };
    return obj;
  },
]);
