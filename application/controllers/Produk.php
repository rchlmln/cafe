<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MY_Controller 
{
	public function index()
	{
		$this->load->view('produk/produk_data');
	}

	public function produk_json()
	{
		$this->load->model('m_produk');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_produk->fetch_data_produk($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['kode_produk'];
			$nestedData[]	= $row['nama_produk'];
			$nestedData[]	= $row['expired_date'];
			$nestedData[]	= $row['kategori'];
			$nestedData[]	= $row['size'];
			$nestedData[]	= $row['harga'];
			$nestedData[]	= ($row['total_stok'] == 'Kosong') ? "<font color='red'><b>".$row['total_stok']."</b></font>" : $row['total_stok'];

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('produk/edit/'.$row['id_produk'])."' id='EditProduk'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('produk/hapus/'.$row['id_produk'])."' id='HapusProduk' ><i class='fa fa-trash-o'></i> Delete</a>";
			}

			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
	}

	public function hapus($id_produk)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_produk');
				$hapus = $this->m_produk->hapus_produk($id_produk);
				if($hapus)
				{
					echo json_encode(array(
						"pesan" => "<font color='green'><i class='fa fa-check'></i> Success delete data !</font>
					"));
				}
				else
				{
					echo json_encode(array(
						"pesan" => "<font color='red'><i class='fa fa-warning'></i> Eror, try again !</font>
					"));
				}
			}
		}
	}

	public function tambah()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				$this->load->library('form_validation');

				$no = 0;
				foreach($_POST['kode'] as $kode)
				{
					$this->form_validation->set_rules('kode['.$no.']','Product Code #'.($no + 1),'trim|required|alpha_numeric|exact_length[5]|callback_exist_kode[kode['.$no.']]');
					$this->form_validation->set_rules('nama['.$no.']','Prodct Name #'.($no + 1),'trim|required|max_length[60]|alpha_numeric_spaces');
					$this->form_validation->set_rules('id_kategori_produk['.$no.']','Category #'.($no + 1),'trim|required');
					$this->form_validation->set_rules('expired_date['.$no.']','Category #'.($no + 1),'required');
					$this->form_validation->set_rules('size['.$no.']','Size #'.($no + 1),'trim|max_length[60]|alpha_numeric_spaces');
					$this->form_validation->set_rules('stok['.$no.']','Stock #'.($no + 1),'trim|required|numeric|min_length[1]|callback_cek_titik[stok['.$no.']]');
					$this->form_validation->set_rules('harga['.$no.']','Price #'.($no + 1),'trim|required|numeric|min_length[4]|callback_cek_titik[harga['.$no.']]');
					$no++;
				}
				
				$this->form_validation->set_message('required','%s please add data !');
				$this->form_validation->set_message('numeric','%s please use number !');
				$this->form_validation->set_message('exist_kode','%s code is already !');
				$this->form_validation->set_message('cek_titik','%s please use number !');
				$this->form_validation->set_message('alpha_numeric_spaces', '%s please use number / alphabet !');
				$this->form_validation->set_message('alpha_numeric', '%s please use number / alphabet !');
				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_produk');

					$no_array = 0;
					$inserted = 0;
					foreach($_POST['kode'] as $k)
					{
						$kode 				= $_POST['kode'][$no_array];
						$nama 				= $_POST['nama'][$no_array];
						$id_kategori_produk	= $_POST['id_kategori_produk'][$no_array];
						$size 				= $_POST['size'][$no_array];
						$expired_date		= $_POST['expired_date'][$no_array];
						$stok 				= $_POST['stok'][$no_array];
						$harga 				= $_POST['harga'][$no_array];

						$insert = $this->m_produk->tambah_baru($kode, $nama, $id_kategori_produk, $size, $expired_date, $stok, $harga);
						if($insert){
							$inserted++;
						}
						$no_array++;
					}

					if($inserted > 0)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<i class='fa fa-check' style='color:green;'></i> Success save data."
						));
					}
					else
					{
						$this->query_error("Oops, Eror, try again !");
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->model('m_kategori_produk');

				$dt['kategori'] = $this->m_kategori_produk->get_all();
				$this->load->view('produk/produk_tambah', $dt);
			}
		}
		else
		{
			exit();
		}
	}

	public function ajax_cek_kode()
	{
		if($this->input->is_ajax_request())
		{
			$kode = $this->input->post('kodenya');
			$this->load->model('m_produk');

			$cek_kode = $this->m_produk->cek_kode($kode);
			if($cek_kode->num_rows() > 0)
			{
				echo json_encode(array(
					'status' => 0,
					'pesan' => "<font color='red'>Code is already</font>"
				));
			}
			else
			{
				echo json_encode(array(
					'status' => 1,
					'pesan' => ''
				));
			}
		}
	}

	public function exist_kode($kode)
	{
		$this->load->model('m_produk');
		$cek_kode = $this->m_produk->cek_kode($kode);

		if($cek_kode->num_rows() > 0)
		{
			return FALSE;
		}
		return TRUE;
	}

	public function cek_titik($angka)
	{
		$pecah = explode('.', $angka);
		if(count($pecah) > 1){
			return FALSE;
		}
		return TRUE;
	}

	public function edit($id_produk = NULL)
	{
		if( ! empty($id_produk))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'inventory')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_produk');
					
					if($_POST)
					{
						$this->load->library('form_validation');

						$kode_produk 		= $this->input->post('kode_produk');
						$kode_produk_old	= $this->input->post('kode_produk_old');

						$callback			= '';
						if($kode_produk !== $kode_produk_old){
							$callback = "|callback_exist_kode[kode_produk]";
						}

						$this->form_validation->set_rules('kode_produk','Product Code','trim|required|alpha_numeric|max_length[40]'.$callback);
						$this->form_validation->set_rules('nama_produk','Product Name','trim|required|max_length[60]|alpha_numeric_spaces');
						$this->form_validation->set_rules('id_kategori_produk','Category','trim|required');
						$this->form_validation->set_rules('expired_date','Expired Date','required');
						$this->form_validation->set_rules('size','Size','trim');
						$this->form_validation->set_rules('total_stok','Stock','trim|required|numeric|max_length[10]|callback_cek_titik[total_stok]');
						$this->form_validation->set_rules('harga','Price','trim|required|numeric|min_length[4]|max_length[10]|callback_cek_titik[harga]');
						
						$this->form_validation->set_message('required','%s please add data !');
						$this->form_validation->set_message('numeric','%s please use number !');
						$this->form_validation->set_message('exist_kode','%s code is already !');
						$this->form_validation->set_message('cek_titik','%s please use number !');
						$this->form_validation->set_message('alpha_numeric_spaces', '%s please use number / alphabet !');
						$this->form_validation->set_message('alpha_numeric', '%s please use number / alphabet !!');
						
						if($this->form_validation->run() == TRUE)
						{
							$nama 				= $this->input->post('nama_produk');
							$id_kategori_produk	= $this->input->post('id_kategori_produk');
							$expired_date		= $this->input->post('expired_date');
							$size				= $this->input->post('size');
							$stok 				= $this->input->post('total_stok');
							$harga 				= $this->input->post('harga');

							$update = $this->m_produk->update_produk($id_produk, $kode_produk, $nama, $id_kategori_produk, $size, $expired_date, $stok, $harga);
							if($update)
							{
								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Success Update Data</div>"
								));
							}
							else
							{
								$this->query_error();
							}
						}
						else
						{
							$this->input_error();
						}
					}
					else
					{
						$this->load->model('m_kategori_produk');

						$dt['produk'] 	= $this->m_produk->get_baris($id_produk)->row();
						$dt['kategori'] = $this->m_kategori_produk->get_all();
						$this->load->view('produk/produk_edit', $dt);
					}
				}
			}
		}
	}

	public function list_kategori()
	{
		$this->load->view('produk/kategori/kategori_data');
	}

	public function list_kategori_json()
	{
		$this->load->model('m_kategori_produk');
		$level 			= $this->session->userdata('ap_level');

		$requestData	= $_REQUEST;
		$fetch			= $this->m_kategori_produk->fetch_data_kategori($requestData['search']['value'], $requestData['order'][0]['column'], $requestData['order'][0]['dir'], $requestData['start'], $requestData['length']);
		
		$totalData		= $fetch['totalData'];
		$totalFiltered	= $fetch['totalFiltered'];
		$query			= $fetch['query'];

		$data	= array();
		foreach($query->result_array() as $row)
		{ 
			$nestedData = array(); 

			$nestedData[]	= $row['nomor'];
			$nestedData[]	= $row['kategori'];

			if($level == 'admin' OR $level == 'inventory')
			{
				$nestedData[]	= "<a href='".site_url('produk/edit-kategori/'.$row['id_kategori_produk'])."' id='EditKategori'><i class='fa fa-pencil'></i> Edit</a>";
				$nestedData[]	= "<a href='".site_url('produk/hapus-kategori/'.$row['id_kategori_produk'])."' id='HapusKategori'><i class='fa fa-trash-o'></i> Delete</a>";
			}

			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),  
			"recordsTotal"    => intval( $totalData ),  
			"recordsFiltered" => intval( $totalFiltered ), 
			"data"            => $data
			);

		echo json_encode($json_data);
	}

	public function tambah_kategori()
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('kategori','Category','trim|required|max_length[40]|alpha_numeric_spaces');				
				$this->form_validation->set_message('required','%s please add data !');
				$this->form_validation->set_message('alpha_numeric_spaces', '%s please use number / alphabet !');

				if($this->form_validation->run() == TRUE)
				{
					$this->load->model('m_kategori_produk');
					$kategori 	= $this->input->post('kategori');
					$insert 	= $this->m_kategori_produk->tambah_kategori($kategori);
					if($insert)
					{
						echo json_encode(array(
							'status' => 1,
							'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> <b>".$kategori."</b> Success added the category</div>"
						));
					}
					else
					{
						$this->query_error();
					}
				}
				else
				{
					$this->input_error();
				}
			}
			else
			{
				$this->load->view('produk/kategori/kategori_tambah');
			}
		}
	}

	public function hapus_kategori($id_kategori_produk)
	{
		$level = $this->session->userdata('ap_level');
		if($level == 'admin' OR $level == 'inventory')
		{
			if($this->input->is_ajax_request())
			{
				$this->load->model('m_kategori_produk');
				$hapus = $this->m_kategori_produk->hapus_kategori($id_kategori_produk);
				if($hapus)
				{
					echo json_encode(array(
						"pesan" => "<font color='green'><i class='fa fa-check'></i> Success delete the category !</font>
					"));
				}
				else
				{
					echo json_encode(array(
						"pesan" => "<font color='red'><i class='fa fa-warning'></i>Eror, try again !</font>
					"));
				}
			}
		}
	}

	public function edit_kategori($id_kategori_produk = NULL)
	{
		if( ! empty($id_kategori_produk))
		{
			$level = $this->session->userdata('ap_level');
			if($level == 'admin' OR $level == 'inventory')
			{
				if($this->input->is_ajax_request())
				{
					$this->load->model('m_kategori_produk');
					
					if($_POST)
					{
						$this->load->library('form_validation');
						$this->form_validation->set_rules('kategori','Category','trim|required|max_length[40]|alpha_numeric_spaces');				
						$this->form_validation->set_message('required','%s please add data !');
						$this->form_validation->set_message('alpha_numeric_spaces', '%s please use number / alphabet !');

						if($this->form_validation->run() == TRUE)
						{
							$kategori 	= $this->input->post('kategori');
							$insert 	= $this->m_kategori_produk->update_kategori($id_kategori_produk, $kategori);
							if($insert)
							{
								echo json_encode(array(
									'status' => 1,
									'pesan' => "<div class='alert alert-success'><i class='fa fa-check'></i> Success update.</div>"
								));
							}
							else
							{
								$this->query_error();
							}
						}
						else
						{
							$this->input_error();
						}
					}
					else
					{
						$dt['kategori'] = $this->m_kategori_produk->get_baris($id_kategori_produk)->row();
						$this->load->view('produk/kategori/kategori_edit', $dt);
					}
				}
			}
		}
	}

	public function cek_stok()
	{
		if($this->input->is_ajax_request())
		{
			$this->load->model('m_produk');
			$kode = $this->input->post('kode_produk');
			$stok = $this->input->post('stok');

			$get_stok = $this->m_produk->get_stok($kode);
			if($stok > $get_stok->row()->total_stok)
			{
				$size_produk = $get_stok->row()->size != NULL ? ' ' . $get_stok->row()->size : '';
				$nama_produk = '('.$get_stok->row()->kode_produk.') '.$get_stok->row()->nama_produk.$size_produk;
				echo json_encode(array('status' => 0, 'pesan' => "Low stock <b>".$nama_produk."</b> only left <b>".$get_stok->row()->total_stok."</b> !"));
			}
			else
			{
				echo json_encode(array('status' => 1));
			}
		}
	}
}