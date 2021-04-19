<?php 

/******************************************
TP4 DPBO - Syauqi (1904312)
******************************************/

class Record extends DB{

	var $jenis = ""; // Jenis record

	// Konstruktor
	function Record($jenis, $db_host = '', $db_user = '', $db_password = '', $db_name = ''){
		$this->DB($db_host, $db_user, $db_password, $db_name);
		$this->jenis = $jenis;
	}

	// Menambahkan data baru
	function tambah($id_penghutang = '', $nominal = '', $ket = ''){
		date_default_timezone_set("Asia/Jakarta");
		$tanggal = date("Ymd");
		// Query mysql insert data
		$query = "INSERT INTO `" . $this->jenis . "` (`id_penghutang`, `nominal`, `tanggal`, `ket`) VALUES (".
					"'". $_POST['id_penghutang'] . "', ".
					"'". $_POST['nominal'] . "', ".
					"'". $tanggal . "', ".
					"'". $_POST['ket']. "')";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data
	function getRecord($id = ''){
		// Query mysql select data sesuai jenis recordnya
		$query = "SELECT * FROM " . $this->jenis;

		// Jika ada masukan id penghutang
		if($id != '') $query .= " WHERE id_penghutang = " . $id;

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Menghitung total hutang/bayar
	function hitung($id = ''){
		$this->getRecord($id);
		$total = 0;
		while ($result = $this->getResult()){
			$total += $result['nominal'];
		}
		return $total;
	}

}

?>
