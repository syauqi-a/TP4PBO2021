<?php 

/******************************************
TP4 DPBO - Syauqi (1904312)
******************************************/

class Penghutang extends DB{

	// Menambahkan data penghutang
	function tambah($nama = '', $jk = '', $pekerjaan = '', $no_hp = '', $alamat = ''){
		// Query mysql insert data ke penghutang
		$query = "INSERT INTO `penghutang` (`nama`, `jk`, `pekerjaan`, `no_hp`, `alamat`) VALUES ('{$nama}', '{$jk}', '{$pekerjaan}', '{$no_hp}', '{$alamat}')";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Mengambil data
	function getPenghutang($id = '', $key = ''){
		// Query mysql select data ke penghutang
		$query = "SELECT * FROM `penghutang`";

		// Jika ada masukan id penghutang
		if ($id!='') $query .= " WHERE `id` = ".$id;

		// Jika ada masukan kata kunci pencarian
		if ($key!='') $query .= " WHERE UCASE(`nama`) LIKE '{$key}'";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Memperbarui data penghutang
	function ubah($id = '', $nama = '', $jk = '', $pekerjaan = '', $no_hp = '', $alamat = ''){
		// Query mysql update data ke penghutang
		$query = "UPDATE `penghutang` SET `nama` = '{$nama}', `jk` = '{$jk}', `pekerjaan` = '{$pekerjaan}', `no_hp` = '{$no_hp}', `alamat` = '{$alamat}' WHERE `id` = {$id}";

		// Mengeksekusi query
		return $this->execute($query);
	}

	// Menghapus data penghutang berdasarkan id (default: hapus semua data)
	function hapus($id = ''){
		// Query mysql delete data ke penghutang
		$query = "DELETE FROM `penghutang`";

		// Jika ada masukan id penghutang
		if ($id!='') $query .= " WHERE `id` = ".$id;

		// Mengeksekusi query
		return $this->execute($query);
	}

}

?>
