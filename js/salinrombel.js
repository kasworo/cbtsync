var ajaxku;
function pilkelas(id){
    ajaxku = buatajax();
    url="rombel_salin.php?k="+id;
    ajaxku.onreadystatechange=KelasChanged;
    ajaxku.open("GET",url,true);
    ajaxku.send(null);
}

function pilrombelasl(id){
    ajaxku = buatajax();
    url="rombel_salin.php?r="+id;
    ajaxku.onreadystatechange=RombelChanged;
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

function KelasChanged(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
    document.getElementById("rombelasl").innerHTML = data
    }else{
    document.getElementById("rombelasl").value = "<option selected>..Pilih..</option>";
    }
    }
}

function RombelChanged(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
    document.getElementById("rombelnew").innerHTML = data
    }else{
    document.getElementById("rombelnew").value = "<option selected>..Pilih..</option>";
    }
    }
}