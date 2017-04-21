<!doctype html>
<html>
<head>
    <title>Appedo Waterfall Chart</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="css/harViewer.css" type="text/css">
</head>
<body class="harBody">
    <div id="content" version="2.0.17"></div>
    <script src="scripts/jquery.js"></script>
    <script data-main="scripts/harViewer" src="scripts/require.js"></script>
    <?php include("ga.php") ?>
<script>
$("#content").bind("onViewerPreInit", function(event)
{
    // Get application object
    var viewer = event.target.repObject;

    // Remove unnecessary tabs
    viewer.removeTab("Home");
    viewer.removeTab("DOM");
    viewer.removeTab("About");
    viewer.removeTab("Schema");

    //Hide the tab bar
    viewer.showTabBar(false);
});
</script>
</body>
</html>
