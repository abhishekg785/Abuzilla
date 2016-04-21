function getDate()
{
  alert("hello you are in the date function()");
  d = new Date();
     mon = d.getMonth()+1;
     time = d.getDate()+"-"+mon+"-"+d.getFullYear()+" "+d.getHours()+":"+d.getMinutes();
   myform.date.value=time;

}
