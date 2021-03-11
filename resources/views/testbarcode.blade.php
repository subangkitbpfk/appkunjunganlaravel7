<!DOCTYPE html>
<html>
<head>
    <title>How to Generate QR Code in Laravel 6? - ItSolutionStuff.com</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
</head>
<body>
<video id="preview"></video>
<button id="opencamera">open</button>
<button id="stopcamera">Exit</button>
    
<div class="visible-print text-center">
    <h1>Test Qrcode Laravels</h1>
   	{!! QrCode::size(75)->generate($a); !!}
</div>
<script type="text/javascript">
	$("#opencamera").click(function(){
	  let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      scanner.addListener('scan', function (content) {
        alert(content);
      });
      Instascan.Camera.getCameras().then(function (cameras) {
        if (cameras.length > 0) {
          scanner.start(cameras[0]);
        } else {
          console.error('No cameras found.');
        }
      }).catch(function (e) {
        console.error(e);
      });
	})

	$("#stopcamera").click(function(){
	       	
		})
      // let scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
      // scanner.addListener('scan', function (content) {
      //   alert(content);
      // });
      // Instascan.Camera.getCameras().then(function (cameras) {
      //   if (cameras.length > 0) {
      //     scanner.start(cameras[0]);
      //   } else {
      //     console.error('No cameras found.');
      //   }
      // }).catch(function (e) {
      //   console.error(e);
      // });


</script>
    
</body>
</html>