<html>

<body>
  <script>
    function showFull(src, pushHistory = true) {
      var thumbnails = document.getElementById('speedtest-thumbnails');
      var fullview = document.getElementById('speedtest-fullview');
      fullview.querySelector('img').src = src;
      thumbnails.style.display = 'none';
      fullview.style.display = 'block';
      if (pushHistory) {
          var params = new URLSearchParams(window.location.search);
          params.set('speedtest', src);
          var newUrl = window.location.pathname + '?' + params.toString();
          history.pushState({view:'full', src: src}, '', newUrl);
      }
      return false;
    }
    window.onpopstate = function(e) {
      var thumbnails = document.getElementById('speedtest-thumbnails');
      var fullview = document.getElementById('speedtest-fullview');
      if (e.state && e.state.view === 'full') {
        thumbnails.style.display = 'none';
        fullview.style.display = 'block';
      } else {
        fullview.style.display = 'none';
        thumbnails.style.display = 'block';
      }
    };

    window.addEventListener('DOMContentLoaded', function() {
        var params = new URLSearchParams(window.location.search);
        var img = params.get('speedtest');
        if (img) {
            showFull(img, false);
        }
    });
  </script>
    <div class="panel panel-default">
        <div class="panel-body ">
            <img src="plugins/Speedtest/images/speedtest-logo.png" title="Speedtest Logo" class="device-icon-header pull-left" style="max-height:25px;height:100%;margin-top:8px">
            <div class="pull-left" style="margin-top: 5px;">
                <span style="font-size: 12px;font-weight: bold">Last used Speedtest server:</span><br />
                <span style="font-size: 12px;"><?php require_once("plugins/Speedtest/tmp/speedtest-server");?></span>
            </div>
            <div class="pull-right">
            <img src="plugins/Speedtest/images/ookla-logo.png" title="Ookla Logo" style="max-height: 50px">
            </div>
        </div>
    </div>

    <div id="speedtest-thumbnails" style='clear: both;'>
        <div style='margin: 5px;'>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Bandwidth</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-bandwidth-day.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-bandwidth-day.png\' style=\'border:0; max-width:800px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-bandwidth-day.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-bandwidth-week.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-bandwidth-week.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-bandwidth-week.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-bandwidth-month.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-bandwidth-month.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-bandwidth-month.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-bandwidth-year.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-bandwidth-year.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-bandwidth-year.png" style="border: 0" loading="lazy"/></a>
                    </div>
                </div>
            </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Latency during Speedtest</h3>
                </div>
                <div class="panel-body">
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-latency-day.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-latency-day.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-latency-day.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-latency-week.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-latency-week.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-latency-week.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-latency-month.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-latency-month.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-latency-month.png" style="border: 0" loading="lazy"/></a>
                    </div>
                    <div class="col-md-3">
                        <a onclick="return showFull('plugins/Speedtest/png/speedtest-latency-year.png');" onmouseover="return overlib('<div class=\'overlib-contents\'><img src=\'plugins/Speedtest/png/speedtest-latency-year.png\' style=\'border:0; max-width:700px; height:auto;\' /></div>',FGCOLOR,'#ffffff', BGCOLOR, '#e5e5e5', BORDER, 5, CELLPAD, 4, CAPCOLOR, '#555555', TEXTCOLOR, '#3e3e3e',WRAP,HAUTO,VAUTO); " onmouseout="return nd();"><img class="img-responsive" src="plugins/Speedtest/png/speedtest-latency-year.png" style="border: 0" loading="lazy"/></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <?php

    ?>
  <div id="speedtest-fullview" style="display:none; text-align:center; margin-top:10px;">
    <img id="speedtest-fullview-img" class="img-responsive" style="margin:auto; max-width:100%;" />
  </div>
</body>

</html>
