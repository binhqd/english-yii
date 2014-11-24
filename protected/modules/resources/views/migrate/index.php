<html>
<head>
<script type="text/javascript" src="/myzone_v1/js/jquery-1.8.2.min.js"></script>
</head>
<body>
<script language='javascript'>
var n = 0;
function migrate() {
	$.ajax({
		url : '<?php echo ZoneRouter::createUrl('/resources/migrate/migrate?file=3')?>',
		success : function(res) {
			if (!res.error) {
				n += res.n;
				$('#var').html(n);
				migrate();
			} else {
				alert(res.message);
			}
		}
	});
}
$(document).ready(function() {
	migrate();
});
</script>
<span id='var'>0</span>/<?php echo $fileCount?> files has migrated

</body>
</html>