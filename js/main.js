function get_id(x){
  return document.getElementById(x);
}
function replyToPm(pmid,user,ta,btn,osender){ 
  var data = get_id(ta).value;
  if(data == ""){
    alert("Type something first weenis");
    return false;
  }
  get_id(btn).disabled = true;
  var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      var datArray = ajax.responseText.split("|");
      if(datArray[0] == "reply_ok"){
        var rid = datArray[1];
        data = data.replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/n/g,"<br />").replace(/r/g,"<br />");
        get_id("pm_"+pmid).innerHTML += '<p><b>Reply by you just now:</b><br />'+data+'</p>';
        expand("pm_"+pmid);
        get_id(btn).disabled = false;
        get_id(ta).value = "";
      } else {
        alert(ajax.responseText);
      }
    }
  }
  ajax.send("action=pm_reply&pmid="+pmid+"&user="+user+"&data="+data+"&osender="+osender);
}

function deletePm(pmid,wrapperid,originator){
  var conf = confirm(originator+"Press OK to confirm deletion of this message and its replies");
  if(conf != true){
    return false;
  }
  var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "delete_ok"){
        get_id(wrapperid).style.display = 'none';
      } else {
        alert(ajax.responseText);
      }
    }
  }
  ajax.send("action=delete_pm&pmid="+pmid+"&originator="+originator);
}

function markRead(pmid,originator){
  var ajax = ajaxObj("POST", "php_parsers/pm_system.php");
  ajax.onreadystatechange = function() {
    if(ajaxReturn(ajax) == true) {
      if(ajax.responseText == "read_ok"){
        alert("Message has been marked as read");
      } else {
        alert(ajax.responseText);
      }
    }
  }
  ajax.send("action=mark_as_read&pmid="+pmid+"&originator="+originator);
}
