<?php
  include "phpGPS.php";
  $db = new phpGPS_DB();
  $con = $db->connectToDB();
  
  $userGroups = array(
      "admin",
      "users"
  );
  
  function showDevices($con) {
    // Select all the rows in the markers table
    $query = "SELECT \n" .
             "  gd.*, \n" .
             "  gowner.gps_owner_name as gps_owner_name \n" .
             "FROM \n" .
             "  gps_device gd \n" .
             "  left join gps_owner gowner on gd.gps_device_id = gowner.gps_owner_id \n" .
             "ORDER BY \n" .
             "  gd.gps_device_id;";
    $result = mysqli_query($con, $query);
    if (!$result) {
      die('Invalid query: ' . mysql_error());
    }
    
    echo "<table border='1' style='width:100%' class='table'>\n";
    echo "<thead><tr>\n";
    echo "<th>id</th>\n";
    echo "<th>IMEI</th>\n";
    echo "<th>Название</th>\n";
    echo "<th>Описание</th>\n";
    echo "<th>Комментарий</th>\n";
    echo "<th>Водитель</th>\n";
    echo "<th>Удаление</th>\n";
    echo "</tr></thead>\n";
    
    echo "<tbody>\n";
    while ($row = @mysqli_fetch_assoc($result)){
      
      $ownersSQL = "SELECT gps_owner_id, gps_owner_name from gps_owner \n" .
          "order by gps_owner_id";
      $ownersDropDown = buildDropDown($con, $ownersSQL, $row['gps_owner_id'], "owner", "gps_device", "gps_owner_id", "gps_owner_name", "gps_device_id", $row['gps_device_id'], true);
      
      echo "<td>" . $row['gps_device_id']      . "</td>\n";
      echo getTableRow($row, "gps_device_local_id", "gps_device", "gps_device_id", "", "");
      echo getTableRow($row, "gps_device_name", "gps_device", "gps_device_id", "", "");
      echo getTableRow($row, "gps_device_desc", "gps_device", "gps_device_id", "", "");
      echo getTableRow($row, "gps_device_comment", "gps_device", "gps_device_id", "", "");
      echo "<td>" . $ownersDropDown . "</td>\n";
      echo "<td><a onclick='updateDelete(\"gps_entries\", \"gps_device_id\", \"NULL\", \"gps_device\", \"gps_device_id=" . $row['gps_device_id']  . "\", true)' href='javascript:void(0);'>[X]</a></td>\n";
      echo "</tr>\n";
    }
    echo "</tbody>\n";
    echo "</table>\n";
  }
?>
<?php 
  $title = "GPS устройство";
  include 'header.php'; 
?>
<h3><?php echo $title; ?></h3>
<hr />
<a onclick='downloadAndRefresh("insertRecord.php?insert=true&table=gps_device&gps_device_name=New Device")' href='javascript:void(0);'>Новое устройство</a><br />
<hr />
<?php showDevices($con); ?>

<?php include 'footer.php'; ?>
