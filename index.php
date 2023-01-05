<!DOCTYPE html>
<html lang="en" ng-app="analytics_app">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GMMR ANALYTICS</title>
    <link rel="stylesheet" href="lib/fontawesome2/css/all.min.css">
    <link rel="stylesheet" href="lib/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="templates/style.css">
</head>

<body>
    <ui-view></ui-view>
</body>
<!-- angular main modules -->
<script src="lib/angular/angular.min.js"></script>
<script src="lib/angular/angular-filter.js"></script>
<script src="lib/angular/angular-idle.min.js"></script>
<script src="lib/angular-sanitize/angular-sanitize.min.js"></script>
<script src="lib/angular-animate/angular-animate.min.js"></script>

<!-- routes -->
<script src="lib/@uirouter/angularjs/release/angular-ui-router.min.js"></script>

<!-- templates -->
<script src="lib/ui-bootstrap4/dist/ui-bootstrap-tpls.js"></script>
<script src="lib/jquery/jquery.min.js"></script>
<script src="lib/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/angular-chart.js/chart.js"></script>
<script src="lib/angular-chart.js/angular-chart.js"></script>

<!-- controllers -->
<script src="src/app.js"></script>
<script src="src/factory.js"></script>
<script src="src/home.controller.js"></script>
<script src="src/diagram.controller.js"></script>

</html>