var ajaxku;
function pilkelas(id){
    ajaxku = buatajax();
    var url="rombel_getid.php";
    url=url+"?k="+id;
    ajaxku.onreadystatechange=levChanged;
    ajaxku.open("GET",url,true);
    ajaxku.send(null);
}

function getkelas(id){
    ajaxku = buatajax();
    var url="rombel_getid.php";
    url=url+"?k="+id;
    ajaxku.onreadystatechange=KelasChanged;
    ajaxku.open("GET",url,true);
    ajaxku.send(null);
}

function buatajax(){
    if (window.XMLHttpRequest){
    return new XMLHttpRequest();
    }
    if (window.ActiveXObject){
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}

function levChanged(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
        document.getElementById("idrombel").innerHTML = data
    }else{
    document.getElementById("idrombel").value = "<option selected>..Pilih..</option>";
    }
    }
}


function KelasChanged(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
        document.getElementById("idrombasl").innerHTML = data;
        document.getElementById("idrombtjn").innerHTML = data;

    }else{
        document.getElementById("idrombasl").value = "<option selected>..Pilih..</option>";
        document.getElementById("idrombtjn").value = "<option selected>..Pilih..</option>";
    }
    }
}