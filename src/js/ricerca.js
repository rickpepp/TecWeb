//funzione ajax per ricerca interattiva
function show_search(str) {
    if (str.length==0) {
        document.getElementById("livesearch").innerHTML="";
        document.getElementById("livesearch").style.border="0px";
        return;
    }
    let xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange=function() {
        if (this.readyState==4 && this.status==200) {
            document.getElementById("livesearch").innerHTML=this.responseText;
        }
    }
    xmlhttp.open("GET","../libs/live_search.php?valore="+str,true);
    xmlhttp.send();
}