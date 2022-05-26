var ajaxku;

function getKelas(id){
    ajaxku = buatajax();
    var url="hasil_seting.php";
    url=url+"?ts="+id;
    ajaxku.onreadystatechange=KelasTukar;
    ajaxku.open("GET",url,true);
    ajaxku.send(null);
}
function getRombel(id){
    ajaxku = buatajax();
    var url="hasil_seting.php";
    url=url+"?kl="+id;
    ajaxku.onreadystatechange=RombelTukar;
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

function KelasTukar(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
        document.getElementById("hsl_kls").innerHTML = data;

    }else{
        document.getElementById("hsl_kls").value = "<option selected>..Pilih..</option>";
    }
    }
}

function RombelTukar(){
    var data;
    if (ajaxku.readyState==4){
    data=ajaxku.responseText;
    if(data.length>=0){
        document.getElementById("hsl_rmb").innerHTML = data;

    }else{
        document.getElementById("hsl_rmb").value = "<option selected>..Pilih..</option>";
    }
    }
}