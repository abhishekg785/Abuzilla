//deletes the answer//
function deleteAnswer(ansId,qId){
//  var id=document.getElementById("ansSec"+qId);
//var deleteButId=document.getElementById("deleteBut"+ansId);
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
              document.getElementById("ansSec"+qId).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","deleteAnswer.php?ansId="+ansId+"&qId="+qId,true);
      xmlhttp.send();

}

//deletes Comment
function deleteComment(comId,pId){   //id of each comment
//var id=document.getElementById("delComBut"+comId);
//var sec=document.getElementById("commSec"+pId);
if(window.XMLHttpRequest){
  xmlhttp=new XMLHttpRequest();
}
else{
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
}
xmlhttp.onreadystatechange=function(){

             if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
              document.getElementById("commSec"+pId).innerHTML=xmlhttp.responseText;

             }
           }
           xmlhttp.open("REQUEST","deleteCommentOfPost.php?comId="+comId+"&postId="+pId,true);
    xmlhttp.send();
}
