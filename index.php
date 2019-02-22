<?php require_once('Connections/koneksi.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

$currentPage = $_SERVER["PHP_SELF"];

$maxRows_perangkat = 5;
$pageNum_perangkat = 0;
if (isset($_GET['pageNum_perangkat'])) {
  $pageNum_perangkat = $_GET['pageNum_perangkat'];
}
$startRow_perangkat = $pageNum_perangkat * $maxRows_perangkat;

mysql_select_db($database_koneksi, $koneksi);
$query_perangkat = "SELECT * FROM perangkat";
$query_limit_perangkat = sprintf("%s LIMIT %d, %d", $query_perangkat, $startRow_perangkat, $maxRows_perangkat);
$perangkat = mysql_query($query_limit_perangkat, $koneksi) or die(mysql_error());
$row_perangkat = mysql_fetch_assoc($perangkat);

if (isset($_GET['totalRows_perangkat'])) {
  $totalRows_perangkat = $_GET['totalRows_perangkat'];
} else {
  $all_perangkat = mysql_query($query_perangkat);
  $totalRows_perangkat = mysql_num_rows($all_perangkat);
}
$totalPages_perangkat = ceil($totalRows_perangkat/$maxRows_perangkat)-1;

$queryString_perangkat = "";
if (!empty($_SERVER['QUERY_STRING'])) {
  $params = explode("&", $_SERVER['QUERY_STRING']);
  $newParams = array();
  foreach ($params as $param) {
    if (stristr($param, "pageNum_perangkat") == false && 
        stristr($param, "totalRows_perangkat") == false) {
      array_push($newParams, $param);
    }
  }
  if (count($newParams) != 0) {
    $queryString_perangkat = "&" . htmlentities(implode("&", $newParams));
  }
}
$queryString_perangkat = sprintf("&totalRows_perangkat=%d%s", $totalRows_perangkat, $queryString_perangkat);

?>
<p><strong><center>TES PRAKTEK TENAGA AHLI PROGRAMMER 2019</center></strong></p>
<center>
  <a href="create.php">Tambah Data</a>
</center>
<table width="100%" border="1" align="center">
  <tr>
  <td width="3%" align="center"><strong>No</strong></td>
    <td width="10%" align="center"><strong>ID</strong></td>
    <td width="40%" align="center"><strong>Nama</strong></td>
    <td width="28%" align="center"><strong>Jumlah</strong></td>
    <td width="19%" align="center"><strong>Aksi</strong></td>
  </tr>
  <?php $no=1; do { ?>
    <tr>
     <td align="center"><?php echo $no++; ?></td>
      <td align="center"><?php echo $row_perangkat['id']; ?></td>
      <td align="center"><?php echo $row_perangkat['nama']; ?></td>
      <td align="center"><?php echo $row_perangkat['jumlah']; ?></td>
      <td align="center"><a href="edit.php?id=<?php echo $row_perangkat['id']; ?>">Ubah</a> - <a href="hapus.php?id=<?php echo $row_perangkat['id']; ?>">Hapus</a></td>
    </tr>
    <?php } while ($row_perangkat = mysql_fetch_assoc($perangkat)); ?>
</table>
<table border="0" align="center">
  <tr>
    <td><?php if ($pageNum_perangkat > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_perangkat=%d%s", $currentPage, 0, $queryString_perangkat); ?>">Kembali</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_perangkat > 0) { // Show if not first page ?>
        <a href="<?php printf("%s?pageNum_perangkat=%d%s", $currentPage, max(0, $pageNum_perangkat - 1), $queryString_perangkat); ?>">Sebelumnya</a>
    <?php } // Show if not first page ?></td>
    <td><?php if ($pageNum_perangkat < $totalPages_perangkat) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_perangkat=%d%s", $currentPage, min($totalPages_perangkat, $pageNum_perangkat + 1), $queryString_perangkat); ?>">Selanjutnya</a>
    <?php } // Show if not last page ?></td>
    <td><?php if ($pageNum_perangkat < $totalPages_perangkat) { // Show if not last page ?>
        <a href="<?php printf("%s?pageNum_perangkat=%d%s", $currentPage, $totalPages_perangkat, $queryString_perangkat); ?>">Terakhir</a>
    <?php } // Show if not last page ?></td>
  </tr>
</table>
</p>


<?php
mysql_free_result($perangkat);
?>
