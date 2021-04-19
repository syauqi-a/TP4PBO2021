<?php

/******************************************
TP4 DPBO - Syauqi (1904312)
******************************************/

include("conf.php");
include("includes/Template.class.php");
include("includes/DB.class.php");
include("includes/Penghutang.class.php");
include("includes/Record.class.php");

// Membuat objek dari kelas penghutang
$oPenghutang = new Penghutang($db_host, $db_user, $db_password, $db_name);
// Membuat kelas record hutang
$oHutang = new Record("hutang", $db_host, $db_user, $db_password, $db_name);
// Membuat kelas record bayar hutang
$oBayar = new Record("bayar", $db_host, $db_user, $db_password, $db_name);

$oPenghutang->open();
$oHutang->open();
$oBayar->open();

// Membaca template home.html
$tpl = new Template("templates/home.html");

// Form tambah data penghutang baru
if(isset($_POST['tambah'])){
	// Simpan data penghutang baru
	if(isset($_POST['nama'])) $oPenghutang->tambah($_POST['nama'], $_POST['jk'], $_POST['pekerjaan'], $_POST['no_hp'], $_POST['alamat']);

	// Menampilkan form tambah data penghutang baru
	$data = "<h3>Form tambah data penghutang baru</h3><form action='index.php' method='POST'>
			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='nama'>Nama lengkap</label>
				  <input type='text' class='form-control' name='nama' required />
				</div>
				  </div>

			  <div class='row'>
				<div class='form-group col-md-6'>
				  <label for='jk'>Jenis kelamin</label>
				  <div class='col-sm-10'>
					<div class='form-check form-check-inline'>
					  <input class='form-check-input' type='radio' name='jk' id='jk1' value='l' />
					  <label class='form-check-label' for='jk1'>Laki-laki</label>
					</div>
					<div class='form-check form-check-inline'>
					  <input class='form-check-input' type='radio' name='jk' id='jk2' value='p' />
					  <label class='form-check-label' for='jk2'>Perempuan</label>
					</div>
				  </div>
				</div>
			  </div>

			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='pekerjaan'>Pekerjaan</label>
				  <input type='text' class='form-control' name='pekerjaan' />
				</div>
			  </div>

			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='no_hp'>No HP</label>
				  <input type='text' class='form-control' name='no_hp' />
				</div>
			  </div>

			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='alamat'>Alamat</label>
				  <textarea class='form-control' name='alamat' rows='3'></textarea>
				</div>
			  </div>

			  <button type='submit' name='tambah' class='btn btn-primary'>Tambah Penghutang</button>
			</form><br>";

	// Mengganti kode Data_Tabel dengan data yang sudah diproses
	$tpl->replace("<div id='form'><\/div>", $data);
}

// Form catat hutang atau bayar hutang
if(isset($_POST['catat'])){

	// Cek apakah user telah memilih penghutang dan memasukkan nominal atau belum
	if(isset($_POST['id_penghutang']) && isset($_POST['nominal'])){
		// Simpan hutang atau bayar hutang
		(($_POST['catat'] == "hutang") ? $oHutang->tambah($_POST['id_penghutang'], $_POST['nominal'], $_POST['ket']) : $oBayar->tambah($_POST['id_penghutang'], $_POST['nominal'], $_POST['ket']));
	}

	// Menampilkan form catat hutang atau bayar hutang
	$data = (($_POST['catat'] == "hutang") ? "<h3>Form catat hutang</h3>" : "<h3>Form bayar hutang</h3>");

	$data .= "<form action='index.php' method='POST'>
			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='penghutang'>Penghutang</label>
				  <select class='form-control' name='id_penghutang' required>
					<option value='' hidden>Pilih penghutang</option>";

	// Mengisi daftar pilihan penghutang
	$oPenghutang->getPenghutang();
	while($result = $oPenghutang->getResult()){
		$data .= "<option value={$result['id']}".(isset($_POST['id'])&&($result['id']==$_POST['id']) ? " selected": "").">{$result['nama']}</option>";
	}

	$data .=	  "</select>
				</div>
			  </div>

			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='nominal'>Nominal</label>
				  <input type='number' class='form-control' name='nominal' required />
				</div>
			  </div>

			  <div class='form-row'>
				<div class='form-group col-md-2'>
				  <label for='ket'>Keterangan</label>
				  <textarea class='form-control' name='ket' rows='3'></textarea>
				</div>
			  </div>";

	$data .= (($_POST['catat'] == "hutang") ? "<button type='submit' name='catat' value='hutang' class='btn btn-primary'>Catat Hutang</button>" : "<button type='submit' name='catat' value='bayar' class='btn btn-primary'>Bayar Hutang</button>");

	$data .= "</form><br>";

	// Mengganti kode Data_Tabel dengan data yang sudah diproses
	$tpl->replace("<div id='form'><\/div>", $data);

}

// Menghapus data penghutang
if(isset($_GET['id_hapus'])){
	(($_GET['id_hapus'] == "all") ? $oPenghutang->hapus() : $oPenghutang->hapus($_GET['id_hapus']));
	unset($_GET['id_hapus']);
	header("location: index.php");
}

// Mengedit data penghutang
if(isset($_GET['id_edit'])){
	// Cek apakah ada data penghutang yang memiliki id tersebut
	if ($oPenghutang->getPenghutang($_GET['id_edit'])){
		$result = $oPenghutang->getResult();
		// Menampilkan form edit data penghutang
		$data = "<h3>Form edit data penghutang</h3><form action='index.php' method='POST'>
				  <div class='form-row'>
					<div class='form-group col-md-2'>
					  <label for='nama'>Nama lengkap</label>
					  <input type='text' class='form-control' name='nama' value='{$result['nama']}' required />
					</div>
				  </div>

				  <div class='row'>
					<div class='form-group col-md-6'>
					  <label for='jk'>Jenis kelamin</label>
					  <div class='col-sm-10'>
						<div class='form-check form-check-inline'>
						  <input class='form-check-input' type='radio' name='jk' id='jk1' value='l' ".($result['jk'] == 'l' ? 'checked':'')." />
						  <label class='form-check-label' for='jk1'>Laki-laki</label>
						</div>
						<div class='form-check form-check-inline'>
						  <input class='form-check-input' type='radio' name='jk' id='jk2' value='p' ".($result['jk'] == 'p' ? 'checked':'')." />
						  <label class='form-check-label' for='jk2'>Perempuan</label>
						</div>
					  </div>
					</div>
				  </div>

				  <div class='form-row'>
					<div class='form-group col-md-2'>
					  <label for='pekerjaan'>Pekerjaan</label>
					  <input type='text' class='form-control' name='pekerjaan' value='{$result['pekerjaan']}' />
					</div>
				  </div>

				  <div class='form-row'>
					<div class='form-group col-md-2'>
					  <label for='no_hp'>No HP</label>
					  <input type='text' class='form-control' name='no_hp' value='{$result['no_hp']}' />
					</div>
				  </div>

				  <div class='form-row'>
					<div class='form-group col-md-2'>
					  <label for='alamat'>Alamat</label>
					  <textarea class='form-control' name='alamat' rows='3' >{$result['alamat']}</textarea>
					</div>
				  </div>

				  <input type='text' name='id' value={$result['id']} hidden/>
				  <button type='submit' name='perbarui' class='btn btn-primary'>Simpan Perubahan</button>
				</form><br>";

		// Mengganti kode Data_Tabel dengan data yang sudah diproses
		$tpl->replace("<div id='form'><\/div>", $data);
	}
}

// Memperbarui data penghutang
if(isset($_POST['perbarui']))
	$oPenghutang->ubah($_POST['id'], $_POST['nama'], $_POST['jk'], $_POST['pekerjaan'], $_POST['no_hp'], $_POST['alamat']);

$data = null;
// Melihat data penghutang berdasarkan id penghutang
if(isset($_GET['id_lihat'])){
	// Cek apakah ada data penghutang yang memiliki id tersebut
	if ($oPenghutang->getPenghutang($_GET['id_lihat'])){
		// Menampilkan biodata penghutang
		$bio = $oPenghutang->getResult();
		$data .= "<h3>Biodata ".($bio['jk']=='l' ? 'Mas' : 'Mba')." <span class='text-primary'>{$bio['nama']}</span> <span style='font-size: 12px'><a href='index.php?id_edit={$_GET['id_lihat']}' class='text-success'>&lt;edit&gt;</a> <a href='index.php?id_hapus={$_GET['id_lihat']}' class='text-danger'>&lt;hapus&gt;</a></span></h3>
				  <table style='border: 1px dotted'>
					<tr>
						<th>Jenis Kelamin</th>
						<th> : </th>
						<th>".($bio['jk']=='l' ? 'Laki-laki' : 'Perempuan')."</th>
					</tr>
					<tr>
						<th>Pekerjaan</th>
						<th> : </th>
						<th>{$bio['pekerjaan']}</th>
					</tr>
					<tr>
						<th>No HP</th>
						<th> : </th>
						<th>{$bio['no_hp']}</th>
					</tr>
					<tr>
						<th>Alamat</th>
						<th> : </th>
						<th>{$bio['alamat']}</th>
					</tr>
				  </table><br>";

		$gabung = array();
		// Cek apakah penghutang memiliki data hutang
		if($oHutang->getRecord($_GET['id_lihat'])){
			while($result = $oHutang->getResult()){
				$result["jenis"] = "Hutang";
				array_push($gabung, $result);
			}
		}
		// Cek apakah penghutang memiliki data bayar hutang
		if($oBayar->getRecord($_GET['id_lihat'])){
			while($result = $oBayar->getResult()){
				$result["jenis"] = "Bayar";
				array_push($gabung, $result);
			}
		}
		// Jika penghutang tidak memiliki data hutang dan bayar hutang
		if(empty($gabung)) $data .= "<h3>Tidak memiliki data hutang</h3>";

		// Jika penghutang memiliki data hutang atau bayar hutang
		else{
			// Urutkan data gabungan berdasarkan tanggalnya
			$tanggal = array();
			foreach($gabung as $key => $row){
				$tanggal[$key] = $row['tanggal'];
			}
			array_multisort($tanggal, SORT_ASC, $gabung);
			// Proses mengisi judul tabel
			$data .= "<h3>Rincian hutang</h3>
					<p><form action='index.php' method='POST' style='margin-top: 5px'>
					  <input name='id' value={$_GET['id_lihat']} hidden />
					  <button type='submit' name='catat' value='hutang' class='btn btn-warning' style='font-weight: bold;'>+Hutang</button>
					  <button type='submit' name='catat' value='bayar' class='btn btn-success' style='font-weight: bold;'>+Bayar</button>
					</form></p>
					<table align='left' border='1' style='text-align: center;'>
					  <tr>
						<td>No</td>
						<td>Tanggal</td>
						<td>Jenis</td>
						<td>Nominal</td>
						<td>Keterangan</td>
					  </tr>";
			// Proses mengisi tabel dengan data
			$no = 1;
			$totalHutang = 0;
			foreach($gabung as $val){
				$data .= "<tr><td>" . $no . "</td>
							<td>".$val['tanggal']."</td>
							<td>".$val['jenis']."</td>
							<td>".$val['nominal']."</td>
							<td>".$val['ket']."</td></tr>";
				$totalHutang += ($val['jenis']=='Hutang' ? $val['nominal']:(- $val['nominal']));
				$no++;
			}
			$data .= "</table><br>Total Hutang: Rp{$totalHutang}";
		}
	}
}
// Melihat semua data penghutang atau berdasarkan kata kunci pencarian
else{
	$key = "";
	// Cek apakah ada masukan kata kunci pencarian
	if(isset($_POST['cari'])){
		$key = "%";
		foreach(str_split($_POST['kata_kunci']) as $x) $key .= $x."%";
		$key = strtoupper($key);
	}
	// Memanggil method getPenghutang di kelas Penghutang
	if(mysqli_num_rows($oPenghutang->getPenghutang("", $key)) > 0){
		// Proses mengisi judul tabel
		$data .= ($key=="" ? "<h3>Tabel daftar penghutang <span style='font-size: 12px'><a href='index.php?id_hapus=all' class='text-danger'>&lt;hapus semua&gt;</a></span></h3>" : "<h3>Hasil pencarian dengan kata kunci '<span style='color:blue'>{$_POST['kata_kunci']}</span>'</h3>")."
				<table align='left' border='1' style='text-align: center;'>
				  <tr>
					<td>No</td>
					<td>Nama</td>
					<td>Alamat</td>
					<td>No HP</td>
					<td>Total hutang</td>
					<td>Aksi</td>
				  </tr>";
		$no = 1;

		// Proses mengisi tabel dengan data
		while ($result = $oPenghutang->getResult()) {
			$totalHutang = $oHutang->hitung($result['id']) - $oBayar->hitung($result['id']);

			$data .= "<tr><td>" . $no . "</td>
						  <td>" . $result['nama'] . "</td>
						  <td>" . $result['alamat'] . "</td>
						  <td>" . $result['no_hp'] . "</td>
						  <td>". ($totalHutang != 0 ? "Rp".$totalHutang : "Lunas") ."</td>
						  <td>
							<button class='btn btn-info'><a href='index.php?id_lihat={$result['id']}' style='color: white; font-weight: bold;'>Lihat</a></button>
							<button class='btn btn-danger'><a href='index.php?id_hapus={$result['id']}' style='color: white; font-weight: bold;'>Hapus</a></button>
							<button class='btn btn-primary'><a href='index.php?id_edit={$result['id']}' style='color: white; font-weight: bold;'>Edit</a></button>
							<form action='index.php' method='POST' style='margin-top: 5px'>
							  <input name='id' value={$result['id']} hidden />
							  <button type='submit' name='catat' value='hutang' class='btn btn-warning' style='font-weight: bold;'>+Hutang</button>
							  <button type='submit' name='catat' value='bayar' class='btn btn-success' style='font-weight: bold;'>+Bayar</button>
							</form>
						  </td></tr>";
			$no++;
		}
		$data .= "</table>";
	}
	// Jika tidak menemukan data pada database
	else $data .= "<h3>Tidak ada data penghutang".($key!="" ? " dengan kata kunci '<span style='color:blue'>{$_POST['kata_kunci']}</span>'" : "")."</h3>";
}

// Menutup koneksi database
$oPenghutang->close();
$oHutang->close();
$oBayar->close();

// Mengganti kode Data_Tabel dengan data yang sudah diproses
$tpl->replace("<div id='ubah'><\/div>", $data);

// Menampilkan ke layar
$tpl->write();
?>