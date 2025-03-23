<?php 
 

if (empty($aviso_ok) && isset($_SESSION["setAdviceaviso_ok"]))  $aviso_ok = $_SESSION["setAdviceaviso_ok"];
if (empty($aviso_error) && isset($_SESSION["setAdviceaviso_error"]))  $aviso_error = $_SESSION["setAdviceaviso_error"];
if (empty($aviso_warning) && isset($_SESSION["setAdviceaviso_warning"]))  $aviso_warning = $_SESSION["setAdviceaviso_warning"];
if (empty($aviso_info) && isset($_SESSION["setAdviceaviso_info"]))  $aviso_info = $_SESSION["setAdviceaviso_info"];


$_SESSION["setAdviceaviso_ok"] = "";
$_SESSION["setAdviceaviso_error"] = "";
$_SESSION["setAdviceaviso_warning"] = "";
$_SESSION["setAdviceaviso_info"] = "";
 
 

if (!empty($aviso_ok) && $aviso_ok !=""){
?>
<div class="alert alert-success alert-dismissible show" role="alert" style="margin-top: 15px;">
  <?=$aviso_ok?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php 
}
if (!empty($aviso_error) && $aviso_error !=""){
?>
<div class="alert alert-danger alert-dismissible show" role="alert" style="margin-top: 15px;">
  <?=$aviso_error?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<?php 
}
if (!empty($aviso_warning) && $aviso_warning !=""){
?>
<div class="alert alert-warning alert-dismissible show" role="alert" style="margin-top: 15px;">
<?=$aviso_warning?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php 
}
if (!empty($aviso_info) && $aviso_info !=""){
?>
<div class="alert alert-info alert-dismissible show" role="alert" style="margin-top: 15px;">
  <?=$aviso_info?>
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
<?php 
}
?>