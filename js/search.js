function search(str){
  if(str.length==""){
    document.getElementById("suggest").innerHTML="";
  }
    else{
  if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("suggest").innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","search.php?Name="+str,true);
      xmlhttp.send();
}
}



   //questions part//
   function search2(str)
   {
  if(str.length==""){
       document.getElementById("suggest").innerHTML="";
  }
       else{
  if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("suggest").innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST","search_ques.php?Ques="+str,true);
      xmlhttp.send();
   }
   }


function clearText(){
    document.getElementById("suggest").innerHTML="";
    
}