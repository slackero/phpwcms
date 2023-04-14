//pwmod: ajaxfunction chane visible icon
function SendData(sVar1,sVar2,sVar3,sVar6,stoken) {
  //get the values
  if(sVar1==4 || sVar1==6){sVar4='public';}else{sVar4='visible';}
  sImage= '#'+sVar4 + '_' + sVar1 + '_' + sVar2 + '_' + sVar3;
  sLen = $(sImage).attr('src').length;
  sVar5 = $(sImage).attr('src').substring(sLen-5,sLen-4);
  if(sVar5==1){sVar5=0;}else{sVar5=1;}
  //build url
  var sUrl = 'include/inc_act/ajax_pwcalls.php?action=visible&do=' + sVar1 + ',' + sVar2 + ',' + sVar3 + ',' + sVar5;
  //sends the the value to the php page and change image
  $.ajax({url: sUrl, success: function(data) { $(sImage).attr('src','img/button/' + sVar4 + sVar6 + sVar5 + '.gif'); }});
}

