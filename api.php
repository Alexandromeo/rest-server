<?php

error_reporting(0);
$conn = new PDO("mysql:host=localhost; dbname=vue", "root", "");
$action = $_GET['action'];

if($action=="read")
{
	$nim = $_GET['nim'];
	if($nim)
	{
		if($_GET['search'])
		{
			$getUsers = $conn->prepare("select *from mahasiswa where nim like :nim");
			$getUsers->bindValue(':nim', '%'.$nim.'%');
			$getUsers->execute();

			$users = $getUsers->fetch(PDO::FETCH_OBJ);
			if($users->nim != "")
			{
				$result['status'] = true;
				$result['message'] = "Data berhasil ditemukan";
				$result['mahasiswa'] = $users;
			}

			else
			{
				$result['status'] = false;
				$result['message'] = "Data gagal ditemukan";
			}
		}

		else
		{
			$getUsers = $conn->prepare("select *from mahasiswa where nim = :nim");
			$getUsers->execute(array('nim'=>$nim));
			$users = $getUsers->fetch(PDO::FETCH_OBJ);
			$result['status'] = true;
			$result['message'] = "Data berhasil ditemukan";
			$result['mahasiswa'] = $users;
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
		$result['message'] = "Data berhasil dilihat";
		$result['status'] = true;
		$result['mahasiswa'] = $users;
	}
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
