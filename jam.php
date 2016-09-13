<?php

//Array Hari
$array_hari = array(1=>"Senin","Selasa","Rabu","Kamis","Jumat", "Sabtu","Minggu");
$hari = $array_hari[date("N")];

//Format Tanggal
$tanggal = date ("d");

//Array Bulan
$array_bulan = array(1=>"Januari","Februari","Maret", "April", "Mei", "Juni","Juli","Agustus","September","Oktober", "November","Desember");
$bulan = $array_bulan[date("n")];

//Format Tahun
$tahun = date("Y");
$bulan2 = date("m");

$tgl= date('Y-m-d');	 

?>