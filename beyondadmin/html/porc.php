<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<?php echo '<link id="beyond-link" href="assets/css/beyond.min.css" rel="stylesheet" />';?>
</head>
<body><div id="donut-chart" class="chart chart-lg"></div>
<script src="assets/js/jquery-2.0.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/beyond.min.js"></script>
<script src="assets/js/charts/morris/raphael-2.0.2.min.js"></script>
<script src="assets/js/charts/morris/morris.js"></script>
<script type="text/javascript">
var InitiateDonutChart = function () {
    return {
        init: function () {
            Morris.Donut({
                element: 'donut-chart',
                data: [
                  { label: 'IOS', value: 40 , },
                  { label: 'Win', value: 30 },
                  { label: 'Android', value: 25 },
                  { label: 'Java', value: 5 }
                ],
                colors: [themeprimary, themesecondary, themethirdcolor, themefourthcolor],
                formatter: function (y) { return y + "%" }
            });
        }
    };
}();
$(window).bind("load", function () { InitiateDonutChart.init(); });
</script>
</body>
</html>
