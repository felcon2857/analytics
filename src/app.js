var analytics_app = angular
  .module("analytics_app", [
    "ui.router",
    "ui.bootstrap",
    "ngAnimate",
    "ngSanitize",
    "ngIdle",
    "angular.filter",
    "chart.js",
  ])
  .config(function (
    $stateProvider,
    $urlRouterProvider,
    IdleProvider,
    KeepaliveProvider,
    $locationProvider
  ) {
    $locationProvider.hashPrefix("");
    IdleProvider.idle(6000);
    IdleProvider.timeout(10);
    KeepaliveProvider.interval(7000);
    $urlRouterProvider.otherwise("/home");

    $stateProvider
      // login
      .state("dashboard", {
        url: "/home",
        templateUrl: "templates/home.tpl.php",
        controller: "home_ctrl",
      })
      .state("diagram", {
        url: "/diagram",
        templateUrl: "templates/diagram.tpl.php",
        controller: "diagram_ctrl",
      });
  });
