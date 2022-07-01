<?php
// Datenbanksimulation
$temperature = 42;
$target = 27;
$offset = -1;
// $overwrite = NULL;
$addClass = "";
$userName = "Testuser";
$hardwareID = 47110815;
$time = date('H:i:s', time());

// Form abarbeiten (falls vorhanden)
if ($_POST) {
  if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
    if ($offset=="") $offset=0;
  }
  else {
    $offset = 0;
  }
  if (isset($_POST['overwrite'])) {
    $overwrite = $_POST['overwrite'];
  }

  // Datenbank schreiben
}

// Datenbank lesen

if (isset($overwrite) && $overwrite!=0 && $overwrite!=NULL) {$addClass = "lineThrough";}
if (isset($offset)) {
  if ($offset<0) {$a="";}
  else {$a="+";}
  $targetPrint2 = $target+$offset;
  $targetPrint = $target.$a.$offset."°C = ".$targetPrint2."°C";
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Willkommen</title>
  <link href="./style.css" type="text/css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="./style.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js" integrity="sha384-Xe+8cL9oJa6tN/veChSP7q+mnSPaj5Bcu9mPX5F5xIGE0DVittaqT5lorf0EI7Vk" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-kjU+l4N0Yf4ZOJErLsIcvOU2qSb74wXpOhqTvwVx3OElZRweTnQ6d31fXEoRD1Jy" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container-xl main">
    <div class="row mainrow">

      <div class="col-sm-12 col-md-4 col-lg-4 mainmenueframe">

        <div class="row mainmenue button" id="btn_overview">
          <div class="col-md-3 menueimg">
            <img src="./icons/overview.png" alt="overview" width="75" height="75">
          </div>
          <div class="col-md-9 menuecontent">
            Überblick
          </div>
        </div>

        <div class="row mainmenue button" id="btn_setup">
          <div class="col-md-3 menueimg">
            <img src="./icons/setup.png" alt="setup" width="75" height="75">
          </div>
          <div class="col-md-9 menuecontent">
            Einstellungen
          </div>
        </div>

        <div class="row mainmenue button" id="btn_misc">
          <div class="col-md-3 menueimg">
          <img src="./icons/threedots.png" alt="allother" width="75" height="75">
          </div>
          <div class="col-md-9 menuecontent">
            Sonstiges
          </div>
        </div>
        
      </div>

      <!-- overview start -->
      <div class="col-sm-12 col-md-8 col-lg-8 maincontentframe" id="overview">
        <form action="./index.php" method="post">
          <div class="container">
            <div class="row">

              <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-3">
                      <img src="./icons/thermometer.png" alt="temperature" width="50" height="50">
                    </div>
                    <div class="col-9">aktuelle Temeperatur</div>
                  </div>
                  <div class="row">
                      <div class="col-12 content"><?= $temperature ?>°C</div>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-3">
                      <img src="./icons/thermtarget.png" alt="targettemperature" width="50" height="50">
                    </div>
                    <div class="col-9">Zieltemperatur</div>
                  </div>
                  <div class="row">
                      <div class="col-12 content <?= $addClass ?>"><?= $targetPrint ?></div>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-3">
                      <img src="./icons/offset.png" alt="offset" width="50" height="50">
                    </div>
                    <div class="col-9">Offset</div>
                  </div>
                  <div class="row">
                    <div class="col-12 content input-group">
                      <input type="number" name="offset" class="form-control" aria-describedby="desc-offset" min="-10" max="10" placeholder="-10 - 10" value="<?= $offset ?>">
                      <span class="input-group-text" id="desc-offset">°C</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-3">
                      <img src="./icons/thermtarget.png" alt="targettemperature" width="50" height="50">
                    </div>
                    <div class="col-9">Overwrite</div>
                  </div>
                  <div class="row">
                    <div class="col-12 content input-group">
                      <input type="number" name="overwrite" class="form-control" aria-describedby="desc-overwrite" min="10" max="40" maxlength="2" placeholder="10 bis 40" value="<?= $overwrite ?>">
                      <span class="input-group-text" id="desc-overwrite">°C</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-3">
                      <img src="./icons/thermtarget.png" alt="targettemperature" width="50" height="50">
                    </div>
                    <div class="col-9">Overwrite</div>
                  </div>
                  <div class="row">
                    <div class="col-12 content input-group">
                      <input type="number" name="overwrite" class="form-control" aria-describedby="desc-overwrite" min="10" max="40" maxlength="2" placeholder="10 bis 40" value="<?= $overwrite ?>">
                      <span class="input-group-text" id="desc-overwrite">°C</span>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12 col-md-6 col-lg-4 content-entry-frame">
                <div class="container content-entry">
                  <div class="row">
                    <div class="col-12" style="text-align: center; min-height: 50px;">
                      Änderungen speichern
                    </div>
                  </div>
                  <div class="row">
                      <div class="col-12 content">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </form>
      </div>
      <!-- overview end -->

      <!-- setup start -->
      <div class="col-sm-12 col-md-8 col-lg-8 maincontentframe" id="setup">
        <div class="container">
          <div class="row">

            <div class="col-12 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Setup</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <!-- setup end -->

      <!-- misc start -->
      <div class="col-sm-12 col-md-8 col-lg-8 maincontentframe" id="misc">
        <div class="container">
          <div class="row">

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/user.png" alt="user" width="50" height="50">
                  </div>
                  <div class="col-9">Benutzer</div>
                </div>
                <div class="row">
                  <div class="col-12 content"><?= $userName ?></div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/hardwareid.png" alt="hardwareid" width="50" height="50">
                  </div>
                  <div class="col-9">Hardware-ID</div>
                </div>
                <div class="row">
                  <div class="col-12 content"><?= $hardwareID ?></div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/world.png" alt="timezone" width="50" height="50">
                  </div>
                  <div class="col-9">Zeitzone</div>
                </div>
                <div class="row">
                  <div class="col-12 content">Berlin (MESZ+1)</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/clock.png" alt="time" width="50" height="50">
                  </div>
                  <div class="col-9">Uhrzeit</div>
                </div>
                <div class="row">
                  <div class="col-12 content"><?= $time ?></div>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Misc</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-6 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 content-entry-frame">
              <div class="container content-entry">
                <div class="row">
                  <div class="col-3">
                    <img src="./icons/threedots.png" alt="blabla" width="50" height="50">
                  </div>
                  <div class="col-9">Entry</div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
      <!-- misc end -->

    </div>
  </div>
</body>

</html>