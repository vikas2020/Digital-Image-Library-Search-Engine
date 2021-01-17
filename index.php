<?php include 'header.php'; ?>
        <div id="content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1>Image Search Engine</h1>
                        <form id="form" class="form-horizontal" method="GET" action="searchfunction.php">
                        <div class="input-group">
                          <input type="text" class="form-control" id="speechf" name="search" placeholder="What are you looking for?">
                          <div class="input-group-append">
                            <input type="submit" value="Search" class="btn btn-success" >
                          </div>
                          <div class="input-group-append">
                            <button class="btn btn-warning"  type="button" onclick="startDictation()"><i class="fas fa-microphone"></i></button>
                          </div>
                        </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- HTML5 Speech Recognition API -->
<script>
  function startDictation() {

    if (window.hasOwnProperty('webkitSpeechRecognition')) {

      var recognition = new webkitSpeechRecognition();

      recognition.continuous = false;
      recognition.interimResults = false;

      recognition.lang = "en-US";
      recognition.start();
      
      recognition.onresult = function(e) {
        document.getElementById('speechf').value
                                 = e.results[0][0].transcript;
        recognition.stop();
        //document.getElementById('form').submit();
      };

      recognition.onerror = function(e) {
        recognition.stop();
      }

    }
  }
</script>
</body>
</html>
            
            
