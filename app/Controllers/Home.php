<?php namespace App\Controllers;
use CodeIgniter\Controller;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\M_burger;
use App\Models\KeranjangModel;
use App\Models\MakananModel;

class Home extends BaseController
{
    public function __construct()
    {
        $this->M_burger = new M_burger(); 
    }

    protected $M_burger; 
//login
    public function beranda()   
    {
        if(session()->get('level')>0){ 
            $model= new M_burger;
            $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'

        echo view('header');
        echo view('menu', $data); 
        echo view('beranda'); 
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}
public function error()   
{
    if(session()->get('level')>0){
            $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
            echo view('header');
            echo view('menu', $data);
            echo view('error');
            echo view('footer');
        }else{
            return redirect()->to('http://localhost:8080/home/login');
        }
    }
    public function register()
    {
        $model= new M_burger;
        $data['jel']= $model->tampil('user');
        echo view('header');
        echo view('register',$data);
    }
    public function aksi_t_register()
    {
        $a= $this->request->getPost('nama');
        $b= md5($this->request->getPost('pass'));
        $c= $this->request->getPost('jk');

        $sis= array(
            'level'=>'1',
            'username'=>$a,
            'pw'=>$b,
            'jk'=>$c);
        $model= new M_burger;
        $model->tambah('user',$sis);
        return redirect()-> to ('http://localhost:8080/home/login');
    }
    public function login()
    {
        echo view('header');
        echo view('login');

    }

    public function aksi_login()
    {
        $u=$this->request->getPost('username');
        $p=$this->request->getPost('pw');

        $model = new M_burger();
        $where=array(
            'username'=> $u,
            'pw'=> md5($p)
        );

        $model = new M_burger();
        $cek = $model->getWhere('user',$where);
        
        if ($cek>0){
         session()->set('id',$cek->id_user);
         session()->set('username',$cek->username);
         session()->set('level',$cek->level);
         // Log the login activity
        $model->logActivity($cek->id_user, 'login', 'User logged in.');

        return redirect()->to('home/beranda');
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }

}

public function logout() {
    $user_id = session()->get('id');
    
    if ($user_id) {
        // Log the logout activity
        $model = new M_burger();
        $model->logActivity($user_id, 'logout', 'User logged out.');
    }

    session()->destroy();
    return redirect()->to('http://localhost:8080/home/login');
}






//edit logo website dan text
public function setting()
{
    if (session()->get('level') > 0) {
        $model = new M_burger();
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        
        echo view('header');
        echo view('menu', $data); // Mengirimkan data ke menu.php
        echo view('setting', $data); // Mengirimkan data ke setting.php
        echo view('footer');
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }
}

public function aksietoko()
{
    $model = new M_burger();
    $user_id = session()->get('id');
    $nama = $this->request->getPost('nama');
    $id = $this->request->getPost('id');
    $uploadedFile = $this->request->getFile('foto');

    $where = array('id_toko' => $id);

    $isi = array(
        'nama_toko' => $nama
    );

    // Cek apakah ada file yang diupload
    if ($uploadedFile && $uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
        $foto = $model->uploadgambar($uploadedFile); // Mengupload file baru dan hapus yang lama
        $isi['logo'] = $foto; // Menambahkan nama file baru ke array data
    }

    $model->logActivity($user_id, 'user', 'User changed a profile company');

    $model->editgambar('toko', $isi, $where);

    return redirect()->to('home/setting');
}







public function aksi_e_pesanan()
{
    $model = new M_burger;
    $user_id = session()->get('id');
    $status = $this->request->getPost('status');
    $kodeTransaksi = $this->request->getPost('kode_transaksi');

    // Update status untuk semua transaksi dengan kode_transaksi yang sama
    $where = array('kode_transaksi' => $kodeTransaksi);
    $data = array('status' => $status);
    $model->edit('transaksi', $data, $where);

    $model->logActivity($user_id, 'user', 'User updated a order');
    // Kembalikan JSON response untuk AJAX
    return $this->response->setJSON(['status' => true, 'new_status' => $status]);
}

public function e_pesanan($id)
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $where= array('id_transaksi'=>$id);
        $data['php']=$model->getWhere('transaksi',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('e_pesanan',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}

public function pesanan()
{
    if (session()->get('level') > 0) {
        $model = new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        
        // Fetch and group data by kode_transaksi
        $data['jel'] = $model->joinAndGroupByTransaction();
        $grouped_data = [];
        foreach ($data['jel'] as $kin) {
            $grouped_data[$kin->kode_transaksi][] = $kin;
        }
        $data['grouped_jel'] = $grouped_data;

        echo view('header');
        echo view('menu', $data);
        echo view('pesanan1', $data);
    } else {
        return redirect()->to('http://localhost:8080/home/login');
    }
}




public function activity_log() 
{   
    if(session()->get('level')==2){
    $model = new M_burger();
    $logs = $model->getActivityLogs();

    $data['logs'] = $logs;
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'

    $where = array(
        'id_toko' => 1
    );
    $data['setting'] = $model->getWhere('toko', $where);

    echo view('header');
    echo view('menu', $data);
    return view('activity_log', $data);
    }else{
            return redirect()->to('http://localhost:8080/home/error_404');
    }
}





//pembayaran
public function t_burger()
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $data['jel']= $model->tampil('makanan');
        echo view('header');
        echo view('menu', $data);
        echo view('t_burger',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}
public function aksi_t_burger()
{
    $user_id = session()->get('id');
    $a= $this->request->getPost('nama');
    $b= $this->request->getPost('harga');
    $uploadedFile = $this->request->getfile('foto');
    $foto = $uploadedFile->getName();
    $sis= array(
        'nama'=>$a,
        'harga'=>$b,
        'gambar'=>$foto);
    $model= new M_burger;
    $model->upload($uploadedFile);
    $model->tambah('makanan',$sis);
    $model->logActivity($user_id, 'user', 'User added a new menu burger');
    return redirect()-> to ('http://localhost:8080/home/keranjang');
}
public function e_burger($id)
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $where= array('id_makanan'=>$id);
        $data['php']=$model->getWhere('makanan',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('e_burger',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}
public function aksi_e_burger()
{
    $model= new M_burger;
     $user_id = session()->get('id');
    $id_user = session()->get('id');
    $a= $this->request->getPost('nama');
    $b= $this->request->getPost('harga');
    $id=$this->request->getPost('id');
    $where = array('id_makanan'=>$id);
    $isi= array(
        'nama'=>$a,
        'harga'=>$b);
    $model->edit('makanan',$isi,$where);
    $model->logActivity($user_id, 'user', 'User updated a menu burger data');
    $model->history_edit($id_user, 'Update menu', 'User updated menu.');
    return redirect()-> to ('http://localhost:8080/home/keranjang');
}
public function h_burger($id)
{
    $model= new M_burger;
    $user_id = session()->get('id');
    $kil= array('id_makanan'=>$id);
    $isi= array(
        'deleted_at'=>date('Y-m-d H:i:s'));
    $model->edit('makanan',$isi,$kil);
    $model->logActivity($user_id, 'user', 'User deleted a menu');
    // $model->hapus('makanan',$kil);
    return redirect()-> to('http://localhost:8080/home/keranjang');
}

public function detail_burger($id)
{
    if(session()->get('level')==2 || session()->get('level')==0 ){
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $model= new M_burger;
        $where= array('id_makanan'=>$id);
        $data['php']=$model->getWhere('makanan',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('detail_burger',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/error');
    }
}
public function keranjang()
{   
 if(session()->get('level')>0){
    $model= new M_burger();
         $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
         $where1=array('user.id_user'=>session()->get('id'));

         $data['jel'] = $model->jointigawhere('keranjang','makanan','user','keranjang.id_makanan=makanan.id_makanan','keranjang.id_user=user.id_user','keranjang.id_keranjang', $where1);
         $where2=array(
          'deleted_at'=> NULL
      );
         $data['mel']= $model->getWherepol('makanan', $where2);
         //$data['mel']= $model->tampil('makanan');

         $where=array(
          'id_toko'=> 1
      );
         $data['setting'] = $model->getWhere('toko',$where);
         echo view('header');
         echo view ('menu',$data);
         echo view('keranjang',$data);
     }else{
        return redirect()->to('http://localhost:8080/home/login');

    }
}
public function aksi_t_keranjang()
{
    $id_user = session()->get('id'); // Ambil id_user dari session
    $user_id = session()->get('id');
    $id_makanan = $this->request->getPost('id_makanan'); // Adjusted to match the input name
    $jumlah = $this->request->getPost('jumlah');
    $catatan = $this->request->getPost('catatan');

    // Load model M_burger
    $model = new M_burger();

    // Ambil data produk berdasarkan id_makanan
    $produk = $model->getWhere('makanan', ['id_makanan' => $id_makanan]);

    // Check if $produk exists and is not null
    if ($produk) {
        $harga = $produk->harga; // Ambil harga produk
        // Hitung total harga
        $total_harga = $jumlah * $harga;

        // Data untuk dimasukkan ke tabel keranjang
        $data = [
            'id_makanan' => $id_makanan,
            'jumlah' => $jumlah,
            'catatan' => $catatan,
            'id_user' => $id_user,
            'total_harga' => $total_harga,
        ];

        // Simpan data ke tabel keranjang
        $model->tambah('keranjang', $data);

        $model->logActivity($user_id, 'user', 'User adding a new data to a cart');

        return redirect()->to('home/keranjang');
    } else {
        // Handle case where product is not found
        return redirect()->back()->with('error', 'Produk tidak ditemukan.');
    }
}

public function aksi_e_keranjang()
    {
        $request = \Config\Services::request();
        
        // Get form inputs
        $model= new M_burger;
        $id_keranjang = $request->getPost('id_keranjang');
        $jumlah = $request->getPost('jumlah');
        $user_id = session()->get('id');
        
        // Load models
        $keranjangModel = new KeranjangModel();
        $makananModel = new MakananModel();

        // Get the current item in the cart
        $cartItem = $keranjangModel->find($id_keranjang);

        if ($cartItem) {
            $id_makanan = $cartItem['id_makanan'];

            // Get the product details
            $produk = $makananModel->where('id_makanan', $id_makanan)->first();

            // Check if product exists
            if ($produk) {
                $harga = $produk['harga']; // Get the price of the product

                // Calculate the total price
                $total_harga = $jumlah * $harga;

                // Update the cart item
                $keranjangModel->update($id_keranjang, [
                    'jumlah' => $jumlah,
                    'total_harga' => $total_harga,
                ]);

                $model->logActivity($user_id, 'user', 'User updated an item from cart');

                // Set a success message
                session()->setFlashdata('success', 'Jumlah item berhasil diubah.');
            } else {
                // Set an error message
                session()->setFlashdata('error', 'Produk tidak ditemukan.');
            }
        } else {
            // Set an error message
            session()->setFlashdata('error', 'Item keranjang tidak ditemukan.');
        }

        // Redirect back to the previous page
        return redirect()->back();
    }

public function h_keranjang($id)
{
    $model= new M_burger;
    $user_id = session()->get('id');
    $kil= array('id_keranjang'=>$id);
    $model->hapus('keranjang',$kil);
    $model->logActivity($user_id, 'user', 'User deleted an item from cart');
    return redirect()-> to('http://localhost:8080/home/keranjang');
}

public function hapusproduk($id){
    $model = new M_burger();
    $id_user = session()->get('id'); // Ambil ID user dari session
    $activity = 'Menghapus produk'; // Deskripsi aktivitas
    $this->addLog($id_user, $activity);

    // Data yang akan diupdate untuk soft delete
    $data = [
        'isdelete' => 1,
        'deleted_by' => $id_user,
        'deleted_at' => date('Y-m-d H:i:s') // Format datetime untuk deleted_at
    ];

    // Update data produk dengan kondisi id_produk sesuai
    $model->logActivity($id_user, 'user', 'User deleted a product');
    $model->edit('makanan', $data, ['id_makanan' => $id]);

    return redirect()->to('home/keranjang');
}

public function aksi_bayar()
{
    $id_user = session()->get('id');
    $paymentMethod = $this->request->getPost('payment_method');
    $address = $this->request->getPost('address');
    $keranjang = $this->request->getPost('keranjang');

    if (empty($paymentMethod) || empty($address) || empty($keranjang)) {
        return redirect()->back()->with('error', 'Metode pembayaran, alamat pengiriman, dan data keranjang harus diisi.');
    }

    $model = new M_burger();
    $keranjangItems = $model->getWherecon('keranjang', ['id_user' => $id_user]);

    if (empty($keranjangItems)) {
        return redirect()->back()->with('error', 'Keranjang kosong!');
    }

    $kode_transaksi = '';

    foreach ($keranjangItems as $item) {
        if (is_object($item)) {
            $id_makanan = $item->id_makanan;
            $jumlah = $item->jumlah;
            $total_harga = $item->total_harga;

            $p1 = date("YmdHms");
            $kode_transaksi = ($p1 . $id_user);

            $dataTransaksi = [
                'tgl_transaksi' => date('Y-m-d H:i:s'),
                'kode_transaksi' => $kode_transaksi,
                'id_user' => $id_user,
                'id_makanan' => $id_makanan,
                'jumlah' => $jumlah,
                'total_harga' => $total_harga,
                'created_by' => $id_user,
                'status' => 'unconfirmed'
            ];

            if (!$model->tambah1('transaksi', $dataTransaksi)) {
                log_message('error', 'Gagal menyimpan data transaksi: ' . json_encode($dataTransaksi));
                return redirect()->back()->with('error', 'Gagal menyimpan data transaksi.');
            }
        }
    }

    if (!$model->hapus('keranjang', ['id_user' => $id_user])) {
        return redirect()->back()->with('error', 'Gagal menghapus data keranjang.');
    }

     // Log the transaction activity
    $model->logActivity($id_user, 'transaction', 'User made a transaction.');


    session()->setFlashdata('success', 'Pesanan sedang diproses.');

    // Redirect to the printnota method with kode_transaksi
    return redirect()->to('home/printnota/' . $kode_transaksi);
}




//history
public function history()
{
    // Mengecek level pengguna dari session
    if (session()->get('level') > 0) {
        $model = new M_burger();
        $where1 = array('user.id_user' => session()->get('id'));

        // Mengambil data dari tabel 'toko' dan 'transaksi'
        $data['jes'] = $model->tampilgambar('toko');
        $data['jel'] = $model->jointigawhere('transaksi', 'makanan', 'user', 'transaksi.id_makanan=makanan.id_makanan', 'transaksi.id_user=user.id_user', 'transaksi.id_transaksi', $where1);

        // Mengelompokkan data berdasarkan kode_transaksi
        $grouped_data = [];
        foreach ($data['jel'] as $kin) {
            $grouped_data[$kin->kode_transaksi][] = $kin;
        }

        // Menyimpan data yang sudah digabungkan dalam array baru
        $data['grouped_jel'] = $grouped_data;

        // Menampilkan view
        echo view('header');
        echo view('menu', $data);
        echo view('history', $data);
    } else {
        // Redirect ke halaman login jika level pengguna tidak cukup
        return redirect()->to('/home/login');
    }
}




public function e_upload($id)
{
    if(session()->get('level')>0){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $where= array('kode_transaksi'=>$id);
        $data['php']=$model->getWhere('transaksi',$where);
        echo view('header');
        echo view('menu', $data);
        echo view('e_upload',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/login');
    }
}

//upload file
// public function aksi_e_berkas()
// {
//     $id = $this->request->getPost('id'); // Ensure 'id' is the correct key for the form
//     $where = array('id_transaksi' => $id); // Adjust key if necessary
    
//     $uploadedFile = $this->request->getFile('bukti_file');
    
//     if ($uploadedFile && $uploadedFile->isValid()) {
//         $fileName = $uploadedFile->getName();
        
//         // Save the file to the server
//         if ($uploadedFile->move(WRITEPATH . 'uploads', $fileName)) {
//             // Update the database
//             $data = array(
//                 'bukti_file' => $fileName
//             );
            
//             $model = new M_burger();
//             $model->edit('transaksi', $data, $where);
//         }
//     }
    
//     return redirect()->to('http://localhost:8080/home/keranjang');
// }

public function aksi_e_berkas()
{
    $id = $this->request->getPost('id'); // Ensure 'id' is the correct key for the form
    $kode_transaksi = $this->request->getPost('kode_transaksi'); // Fetch kode_transaksi
    $uploadedFile = $this->request->getFile('bukti_file');

    if ($uploadedFile && $uploadedFile->isValid()) {
        $fileName = $uploadedFile->getName();
        
        // Save the file to the server
        if ($uploadedFile->move(WRITEPATH . 'uploads', $fileName)) {
            // Update the database for all records with the same kode_transaksi
            $data = array(
                'bukti_file' => $fileName
            );
            
            $model = new M_burger();
            $model->update_multiple('transaksi', $data, 'kode_transaksi', $kode_transaksi);
        }
    }
    
    return redirect()->to('http://localhost:8080/home/keranjang');
}



public function rate_order()
{
    $kode_transaksi = $this->request->getPost('kode_transaksi');
    $rating = $this->request->getPost('rating');

    $model = new M_burger();

    // Update rating in the database for all transactions with the same kode_transaksi
    $data = [
        'rating' => $rating
    ];

    // Update all transactions with the same kode_transaksi
    $model->updateRatingsByKodeTransaksi($kode_transaksi, $data);

    // Redirect back to the history page
    return redirect()->to('home/history');
}









public function user()
{
    if (session()->get('level') == 2 || session()->get('level') == 0) {
        $model = new M_burger();
        $data['jel'] = $model->tampil('user');
        $data['jes'] = $model->tampilgambar('toko');
        $id = 1; // id_toko yang diinginkan

        // Menyusun kondisi untuk query
        $where = array('id_toko' => $id);

        // Mengambil data dari tabel 'toko' berdasarkan kondisi
        $data['user'] = $model->getWhere('toko', $where);

        // Memuat view
        $data['setting'] = $model->getWhere('toko', $where);

        echo view('header');
        echo view('menu', $data);
        echo view('user', $data);
    } else {
        return redirect()->to(base_url('home/error'));
    }
}

public function aksi_e_user()
{
    $model = new M_burger();
    $id_user = session()->get('id');
    $user_id = session()->get('id');
    $id = $this->request->getPost('id_user');
    $username = $this->request->getPost('username');
    $jenis_kelamin = $this->request->getPost('jk');

    // Tambahkan log untuk memastikan data diterima
    log_message('info', 'Data diterima: ID=' . $id . ', Username=' . $username . ', JK=' . $jenis_kelamin);

    $where = ['id_user' => $id];
    $data = [
        'username' => $username,
        'jk' => $jenis_kelamin,
    ];


    // Log the activity using the user ID
    // $model->logActivity($user_id, 'user', 'User created an account.');
    $model->history_edit($id_user, 'Update menu', 'User updated data user.');  
    $model->logActivity($id_user, 'user', 'User updated a user data.');  

    // Jalankan update
    $model->edit('user', $data, $where);

    return redirect()->to(base_url('home/user'));
}



public function h_user($id)
{
    $model = new M_burger();
    $id_user = session()->get('id');
    $kil = array('id_user' => $id);
    $model->hapus('user', $kil);
    $model->logActivity($id_user, 'user', 'User deleted a user data.');
    return redirect()->to(base_url('home/user'));
}

public function aksi_reset($id)
{
    $model = new M_burger();
    $user_id = session()->get('id');

    $where= array('id_user'=>$id);

    $isi = array(

        'pw' => md5('12')      

    );
    $model->editpw('user', $isi,$where);
    $model->logActivity($user_id, 'user', 'User reset a password');  

    return redirect()->to('home/user');
}


public function t_user()
{
    $model= new M_burger;
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
    $data['jel']= $model->tampil('user');
    echo view('header');
    echo view('menu', $data);
    echo view('t_user',$data);
    echo view('footer');

}
public function aksi_t_user()
{
    $user_id = session()->get('id');
    $a = $this->request->getPost('nama');
    $b = md5($this->request->getPost('pass'));
    $c = $this->request->getPost('jk');
    $u = $this->request->getPost('level');

    // Prepare the data for inserting into the 'user' table
    $sis = array(
        'level' => $u,
        'username' => $a,
        'pw' => $b,
        'jk' => $c
    );

    // Instantiate the model and add the new user data
    $model = new M_burger;
    $model->tambah('user', $sis);

    $model->logActivity($user_id, 'user', 'User added a new account');  

    // Redirect the user after the operation is completed
    return redirect()->to('http://localhost:8080/home/user');
}


public function detail($id)
{
    if(session()->get('level')==2 || session()->get('level')==0 ){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        $where= array('id_user'=>$id);
        $data['php']=$model->getWhere('user',$where);
        echo view('header');
        echo view('menu',$data);
        echo view('detail',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/error');
    }
}




public function restore()
{
    if(session()->get('level')==2 || session()->get('level')==0 ){
        $model= new M_burger;
        $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
        // $data['jel']=$model->tampil('makanan');
        $data['jel']=$model->query('select * from makanan where deleted_at IS NOT NULL');
        echo view('header');
        echo view('menu',$data);
        echo view('restore',$data);
        echo view('footer');
    }else{
        return redirect()->to('http://localhost:8080/home/error');
    }
}
public function aksi_restore($id)
{
    $user_id = session()->get('id');
    $model = new M_burger();

    $where= array('id_makanan'=>$id);
    $isi = array(
        'deleted_at'=>NULL
    );
    $model->edit('makanan', $isi,$where);
    $model->logActivity($user_id, 'makanan', 'User restore a data');  

    return redirect()->to('home/restore');
}



public function history_edit() 
{   
    if(session()->get('level')==2){
    $model = new M_burger();
    $logs = $model->gethistoryedit();

    $data['logs'] = $logs;
    $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'

    $where = array(
        'id_toko' => 1
    );
    $data['setting'] = $model->getWhere('toko', $where);

    echo view('header');
    echo view('menu', $data);
    return view('history_edit', $data);
}else{
            return redirect()->to('http://localhost:8080/home/error_404');
        }
}













//laporan
public function printPDF($kode_transaksi)
{
    $model = new \App\Models\M_burger();

        // Fetch the transaction details
    $where1 = array('transaksi.kode_transaksi' => $kode_transaksi);
    $data['transactions'] = $model->jointigawhere(
        'transaksi', 'makanan', 'user', 
        'transaksi.id_makanan=makanan.id_makanan', 
        'transaksi.id_user=user.id_user', 
        'transaksi.id_transaksi', 
        $where1
    );

        // Load view and convert to HTML
    $html = view('print_template', $data);

        // Initialize Dompdf
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
    $dompdf->render();

        // Output the generated PDF to Browser
    $dompdf->stream("transaksi_$kode_transaksi.pdf", array("Attachment" => false));
}
public function laporan()
{
    if (session()->get('level') == 2 || session()->get('level') == 0) {
        $model = new M_burger();
             $data['jes'] = $model->tampilgambar('toko'); // Mengambil data dari tabel 'toko'
             echo view('header');
             echo view('menu', $data);
             echo view('laporan');
         } else {
            return redirect()->to('http://localhost:8080/home/error');
        }
    }

    public function generate_pdf()
    {
        if (session()->get('level') > 0) {
            $this->laporan_pdf();
        } else {
            return redirect()->to('home/login');
        }
    }

    public function printnota($kode_transaksi)
    {
    // Load model
        $model = new M_burger();
        $where1 = array('user.id_user' => session()->get('id'));

    // Ambil data setting toko
        $where = array(
            'id_toko' => 1
        );
        $data['setting'] = $model->getWhere('toko', $where);

    // Ambil data pesanan berdasarkan kode_pesanan
        $dompdf = new \Dompdf\Dompdf();
        $where2 = array(
            'kode_transaksi' => $kode_transaksi
        );
        $data['elly'] = $model->jointigawhere('transaksi', 'makanan', 'user', 'transaksi.id_makanan=makanan.id_makanan', 'transaksi.id_user=user.id_user', 'transaksi.kode_transaksi', $where2);

        $html = view('printnota', $data);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A6', 'portrait');
        $dompdf->render();
        $dompdf->stream('laporan_pesanan.pdf', array(
            "Attachment" => false
        ));
    }


    private function laporan_pdf()
    {
        $model = new M_burger();

        $start_date = $this->request->getPost('start_date'); 
        $end_date = $this->request->getPost('end_date'); 

        $data['laporan'] = $model->getLaporanByDate($start_date, $end_date); 
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);


        $html = view('laporan_pdf', $data);

        $dompdf->loadHtml($html);
        $dompdf->render();
        $dompdf->stream("laporan.pdf");
    }

    public function generate_excel()
    {
    $model = new M_burger(); // Ensure this model is properly defined and extends the base Model class
    $start_date = $this->request->getPost('start_date'); 
    $end_date = $this->request->getPost('end_date'); 

    // Fetch data for the given date range
    $data['laporan'] = $model->getLaporanByDateForExcel($start_date, $end_date);

    // Create a new Spreadsheet object
    $spreadsheet = new Spreadsheet();
    $spreadsheet->getProperties()
    ->setCreator("Your Name")
    ->setLastModifiedBy("Your Name")
    ->setTitle("Laporan Transaksi")
    ->setSubject("Laporan Transaksi")
    ->setDescription("Laporan Transaksi")
    ->setKeywords("Spreadsheet")
    ->setCategory("Report");

    // Set the active sheet
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'ID Transaksi')
    ->setCellValue('B1', 'Tanggal Transaksi')
    ->setCellValue('C1', 'Kode Transaksi')
    ->setCellValue('D1', 'ID Makanan')
    ->setCellValue('E1', 'Jumlah')
    ->setCellValue('F1', 'Total Harga');

    // Populate the spreadsheet with data
    $rowCount = 2;
    foreach ($data['laporan'] as $row) {
        $sheet->setCellValue('A'.$rowCount, $row['id_transaksi'])
        ->setCellValue('B'.$rowCount, $row['tgl_transaksi'])
        ->setCellValue('C'.$rowCount, $row['kode_transaksi'])
        ->setCellValue('D'.$rowCount, $row['id_makanan'])
        ->setCellValue('E'.$rowCount, $row['jumlah'])
        ->setCellValue('F'.$rowCount, $row['total_harga']);
        $rowCount++;
    }

    // Set the headers for the Excel file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="laporan_transaksi.xlsx"');
    header('Cache-Control: max-age=0');

    // Write the file and output to the browser
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}

public function generate_window()
{
    if (session()->get('level') > 0) {
        echo view('header');
        echo view('menu');
        echo view('laporan');
        echo view('footer');
    } else {
        return redirect()->to('home/login');
    }
}

public function generate_window_result()
{
    if (session()->get('level') > 0) {
        // Ambil data formulir berdasarkan rentang waktu dari request POST
        $start_date = $this->request->getPost('start_date');
        $end_date = $this->request->getPost('end_date');
        $model = new M_burger();
        $data['formulir'] = $model->getLaporanByDate($start_date, $end_date);

        echo view('cetak_hasil', $data);
    } else {
        return redirect()->to('home/login');
    }


}






}