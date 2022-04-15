<?php
include "dbfunction.php";
function GetUserId()
{
    $qskul = "SELECT kdskul FROM tbskul";
    $sk = vquery($qskul)[0];
    $kdskul = $sk['kdskul'];

    $sql = "SELECT COUNT(*) as juser FROM tbuser u WHERE u.level='2'";
    $row = vquery($sql)[0];
    $id = $row['juser'] + 1;
    if ($id < 8) {
        $cekdigit = 10 - ($id % 9 + 1);
    } else {
        $cekdigit = 10 - ($id % 8 + 1);
    }
    $iduser = 'G' . substr($kdskul, -8) . substr('0000' . $id . $cekdigit, -5);
    return $iduser;
}


$sqluser = "SELECT*FROM tbgtk g INNER JOIN tbskul USING(idskul) WHERE deleted='0'";
$du = vquery($sqluser);
$sukses = 0;
foreach ($du as $u) {
    if ($u['username'] == NULL || $u['username'] == '') {
        $password = str_replace("-", "", $u['tgllahir']);
        $user = GetUserId();
        $paswd = password_hash($password, PASSWORD_DEFAULT);
        $keyusr = array(
            'username' => $user,
            'level' => '2'
        );
        if (cekdata('tbuser', $keyusr) > 0) {
            $dtusr = array(
                'namatmp' => $u['nama'],
                'passwd' => $paswd,
            );
            if (editdata('tbuser', $dtusr, '', $keyusr) > 0) {
                $angka = 1;
            }
        } else {
            $dtusr = array(
                'username' => $user,
                'namatmp' => $u['nama'],
                'passwd' => $paswd,
                'level' => '2',
                'aktif' => '1'
            );
            if (adddata('tbuser', $dtusr) > 0) {
                $angka = 1;
            }
        }
        if ($angka >= 1) {
            $keygtk = array('idgtk' => $u['idgtk']);
            $dtgtk = array('username' => $user);
            if (editdata('tbgtk', $dtgtk, '', $keygtk) > 0) {
                echo '1';
            }
        }
    } else {
        $password = str_replace("-", "", $u['tgllahir']);
        $user = $u['username'];
        $paswd = password_hash($password, PASSWORD_DEFAULT);
        $keyusr = array(
            'username' => $user,
            'level' => '2'
        );
        if (cekdata('tbuser', $keyusr) > 0) {
            $dtusr = array(
                'namatmp' => $u['nama'],
                'passwd' => $paswd,
            );
            if (editdata('tbuser', $dtusr, '', $keyusr) > 0) {
                $angka = 1;
            }
        } else {
            $dtusr = array(
                'username' => $user,
                'namatmp' => $u['nama'],
                'passwd' => $paswd,
                'level' => '2',
                'aktif' => '1'
            );
            if (adddata('tbuser', $dtusr) > 0) {
                $angka = 1;
            }
        }
        if ($angka >= 1) {
            echo 2;
        }
    }
}
