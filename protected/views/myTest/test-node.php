<script language="javascript" src="http://v01.jlbd.com:8080/socket.io/socket.io.js"></script>
<script language="javascript">
var socket = io.connect("http://v01.jlbd.com:8080");
socket.on("notification", function(data) {
	console.log(data);
});
</script>