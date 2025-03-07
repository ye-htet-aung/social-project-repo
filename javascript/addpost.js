var addbutton=document.getElementById("addbutton");
var postbutton=document.getElementById("postbutton");
var cancelbutton=document.getElementById("cancelbutton");

var addposttab=document.getElementById("addpost");
addbutton.addEventListener('click',()=>{
    addposttab.style.display="flex";
})
cancelbutton.addEventListener('click',()=>{
    addposttab.style.display="none";
})