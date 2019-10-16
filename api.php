<?php

error_reporting(0);
$conn = new PDO("mysql:host=localhost; dbname=vue", "root", "");
$action = $_GET['action'];

if($action=="read")
{
	$nim = $_GET['nim'];
	if($_GET['nim'])
	{
		$getUsers = $conn->prepare("select *from mahasiswa where nim = :nim");
		$getUsers->execute(array('nim'=>$_GET['nim']));
		$users = $getUsers->fetch(PDO::FETCH_OBJ);
		$result['status'] = true;
	}

	else
	{
		$getUsers = $conn->prepare("select *from mahasiswa");
		$getUsers->execute();
		$users = array();
		while($user = $getUsers->fetch(PDO::FETCH_OBJ))
		{
			array_push($users, $user);
		}
		$result['status'] = true;
	}
	$result['mesage'] = "Data berhasil dilihat";
	$result['mahasiswa'] = $users;
}

else if($action=="create")
{
	$nim = $_POST['nim'];
	$nama = $_POST['nama'];
	$jurusan = $_POST['jurusan'];
	$data = array
			(
				'nim'=>$nim,
				'nama'=>$nama,
				'jurusan'=>$jurusan
			);
	if(!empty($nim) and !empty($nama and !empty($jurusan)))
	{	
		$insertUser = $conn->prepare("insert into mahasiswa (nim, nama, jurusan) values (:nim, :nama, :jurusan)");
		$insertUser->execute($data);
		
		if($insertUser)
		{
			$result['status'] = true;
			$result['mesage'] = "Data berhasil dimasukkan";
		}

		else
		{
			$result['status'] = false;
			$result['mesage'] = "Data gagal dimasukkan";
		}
	}

	else
	{
		$result['status'] = false;
		$result['mesage'] = "Data harus diisi";
	}
}

else if($action=="update")
{
	$nim = $_POST['nim'];
	$nama = $_POST['nama'];
	$jurusan = $_POST['jurusan'];
	$data = array
			(
				'nim'=>$nim,
				'nama'=>$nama,
				'jurusan'=>$jurusan
			);

	$update = $conn->prepare("update mahasiswa set nama = :nama, jurusan = :jurusan where nim = :nim");
	$update->execute($data);

	if($update)
	{
		$result['message'] = "Berhasil mengubah data";
		$result['status'] = true;
	}

	else
	{
		$result['message'] = "Gagal mengubah data";
		$result['status'] = false;
	}
}

else if ($action == "delete") 
{
	$nim = $_POST['nim'];
	$delete = $conn->prepare("delete from mahasiswa where nim = :nim");
	$delete->bindParam(":nim", $nim);
	$delete->execute();
	if($delete)
	{
		$result['status'] = true;
		$result['message'] = "Data berhasil terhapus";
	}

	else
	{
		$result['status'] = false;
		$result['message'] = "Data gagal terhapus";
	}
}	

else
{
	$getUsers = $conn->prepare("select *from mahasiswa");
	$getUsers->execute();
	$users = array();
	while($user = $getUsers->fetch(PDO::FETCH_OBJ))
	{
		array_push($users, $user);
	}
	$result['status'] = true;
	$result['mahasiswa'] = $users;
}

echo json_encode($result);