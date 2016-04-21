function likeQues(qid,action){
var likeButtonId=document.getElementById("like"+qid);
var unlikeButtonId=document.getElementById("unlike"+qid);
if(action=="likeQues"){
	likeButtonId.style.color="green";
	unlikeButtonId.style.color="black";
}
else if(action=="unlikeQues"){
	likeButtonId.style.color="black";
	unlikeButtonId.style.color="red";
}
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("likestatPor"+qid).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST",action+".php?val="+qid,true);
      xmlhttp.send();
}
//answer section STARTS here//
//in this section we will be dealing with the rating system of the answers given by the user//
function vote(ansId,action){
var upvoteButtonId=document.getElementById("upVote"+ansId);
var downVoteButtonId=document.getElementById("downVote"+ansId);
if(action=="upVote"){
	upvoteButtonId.style.color="green";
	downVoteButtonId.style.color="black";
}
else if(action=="downVote"){
	upvoteButtonId.style.color="black";
	downVoteButtonId.style.color="red";
}
if(window.XMLHttpRequest){
    xmlhttp=new XMLHttpRequest();
  }
  else{
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function(){

               if(xmlhttp.readyState == 4 && xmlhttp.status == 200){
                 document.getElementById("voteStatPor"+ansId).innerHTML=xmlhttp.responseText;

               }
             }
             xmlhttp.open("REQUEST",action+".php?val="+ansId,true);
      xmlhttp.send();
}
