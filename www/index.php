<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
<title>ARK Report</title>
<link rel="stylesheet" href="/ark.css">
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
</head>
<body>
<div class="container">
<div class="row">
<div class="col">
<h2 class="align-middle text-center">ARK instances</h2>
<?php

function scanAllDir($dir) {
  $result = [];
  foreach(scandir($dir) as $filename) {
    if ($filename[0] === '.') continue;
    $filePath = $dir . '/' . $filename;
    if (is_dir($filePath)) {
      foreach (scanAllDir($filePath) as $childFilename) {
        $result[] = $filename . '/' . $childFilename;
      }
    } else {
      $result[] = $filename;
    }
  }
  return $result;
}

include_once "config.inc.php";
$inst_files = scanAllDir($instances_dir);

foreach ($inst_files as $inst_file) {
  if (preg_match("/\.cfg$/",$inst_file)) {
    if (!preg_match("/arkmanager/",$inst_file)) {
      $instances[] = $inst_file;
    }
  }
}

if (count($instances) == 0) {
  echo "<h4>No instance config files found in $instances_dir</h4>";
} else {
  foreach ($instances as $instance) {
    $config_file = $instances_dir . "/" . $instance;
  
    foreach (file($config_file) as $line) {
      #$options[] = preg_grep("/^[a-z]+/", $line);
      if (preg_match("/^[a-z]+/",$line)) {
        $str = explode("=",$line);
        $opts[$str[0]] = strtok($str[1],'"');
      }
    }
  
    echo "<h4 class=\"align-middle text-center\">$instance</h4>";
    echo "<table class=\"table table-sm table-striped w-70\" align=center>";
    echo "<thead><tr>";
    echo "<th>Option</th>";
    echo "<th>Value</th>";
    echo "</tr></thead>";
    echo "<tbody>";
  
    foreach ($opts as $k => $v) {
      if (!preg_match("/password/i",$k)) {
        echo "<tr><td>$k</td>";
        echo "<td>$v</td></tr>";
      }
    }
  
    $version = $_SERVER['DOCUMENT_ROOT']. "/" . $opts[arkserverroot] . "/version.txt";
    echo "<tr><td>ServerVersion</td>";
    echo "<td>" . file_get_contents($version) . "</td></tr>";
  
    echo "</tbody></table>";
  }
}
?>
</div>
<div class="col">
<h2 class="text-center">Survivors</h2>
<div class="text-center">
<?php
$save_dir = $_SERVER['DOCUMENT_ROOT'] . $opts[arkserverroot] . "/ShooterGame/Saved/SavedArks";
$save_files = scandir($save_dir,2);
foreach ($save_files as $save_file) {
  if (preg_match("/\.arkprofile$/",$save_file)) {
    $str = explode(".",$save_file);
    $survivor = $str[0];
    echo "<a href=\"https://steamid.io/lookup/" . $survivor . "\" class=\"text-decoration-none\" target=\"_new\">" . $survivor . "</a>";
  }
}
?>
</div>
</div>
</body>
</html>
