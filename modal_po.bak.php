<!DOCTYPE html>
<html>
<head>
<script>
function showUser(str) {
  if (str=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  } 
  // alert(this.id);
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("txtHint").innerHTML=this.responseText;
    }
  }
  xmlhttp.open("GET","detail_tabel.php?q="+str,true);
  xmlhttp.send();
}
</script>
</head>
<body onload="showUser(this.value)">
  <div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" id="modal_detail">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <p>Details PO</p>
        </div>
        <div class="modal-body">
          <form>
            <select name="po" onChange="showUser(this.value)">
              <!-- <option value="">Select PO:</option> -->
              <option id="nopo_modal" value="<?php echo $no_po;?>"><?php echo $no_po;?></option>
              <option id="nopo_modal" value="PO-HTS/2016/05/0005">PO-HTS/2016/05/0005</option>
              <!-- <option value="<?php echo $no_po; ?>"><?php echo $no_po; ?></option> -->
            </select>
          </form>
          <br>
          <div id="txtHint">
            <b>Details PO will be listed here.</b>
          </div>
        </div>
        <div class="modal-footer">
          
        </div>
      </div>
    </div>
  </div>

</body>
</html>