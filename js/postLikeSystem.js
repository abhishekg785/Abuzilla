function like(id,locationOfPage){
  var likeButton=document.getElementById("like"+id);
    var UnlikeButton=document.getElementById("unlike"+id);
  if(locationOfPage=="likePost"){
    likeButton.style.color="green";
    UnlikeButton.style.color="black";
  }
  else{
    likeButton.style.color="black";
    UnlikeButton.style.color="red";
  }
  if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("postStatId"+id).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST",locationOfPage+".php?val="+id,true);
      xmlhttp.send();

 }
