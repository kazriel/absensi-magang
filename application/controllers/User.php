<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        is_login();
        $this->load->model('profile_model', 'profile');
        //$this->load->model('anggota_model', 'anggota');
        $this->load->model('user_model', 'user');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['page'] = 'user/index';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->load->view('templates/app', $data);
    }

    public function profile()
    {
        $this->form_validation->set_rules('name', 'Nama', 'required|trim', [
            'required' => 'Nama tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
            'required' => 'Email tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('nomorhp', 'Nomor HP', 'required|trim', [
            'required' => 'Nomor HP tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim', [
            'required' => 'Alamat tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('sosmed', 'Sosial Media', 'required|trim', [
            'required' => 'Sosial Media tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('asal', 'Asal', 'required|trim', [
            'required' => 'Asal institusi tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim', [
            'required' => 'Jurusan tidak boleh kosong.'
        ]);
        $this->form_validation->set_rules('durasi', 'Durasi', 'required', [
            'required' => 'Durasi harus dipilih.'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $id = $this->session->userdata('email');
            $user = $this->profile->getProfile($id);

            $data['title']        = 'Biodata';
            $data['page']         = 'user/profile/profile';
            $data['user']         = $user;
            $data['position'] = $this->Crud->ga('positions');
            //$data['position'] = $this->anggota->getPosition();

            $this->load->view('templates/app', $data);
        } else {
            $id = $this->input->post('id');

            $data = [
                'name'    => $this->input->post('name'),
                'email'     => $this->input->post('email'),
                'nomorhp'     => $this->input->post('nomorhp'),
                'alamat'     => $this->input->post('alamat'),
                'sosmed'     => $this->input->post('sosmed'),
                'asal'     => $this->input->post('asal'),
                'jurusan'    => $this->input->post('jurusan'),
                'durasi'    => $this->input->post('durasi'),
                //'image' => $this->input->post('image'),
                'position_id'  => $this->input->post('position_id')
            ];

            // Jika foto diubah
            if (!empty($_FILES['image']['name'])) {
                $upload      = $this->profile->uploadImage();

                // Jika upload berhasil
                if ($upload) {
                    // Ambil data user
                    $imageProfile = $this->profile->getProfile($id);
                    // Hapus foto user sebelum di update
                    if (file_exists('assets/img/image/' . $imageProfile['image']) && $imageProfile['image']) {
                        unlink('assets/img/image/' . $imageProfile['image']);
                    }

                    // Timpa data foto dengan nama yg baru
                    $data['image'] = $upload;
                } else {
                    redirect(base_url("user/profile"));
                }
            }


            $this->profile->updateProfile($id, $data);
            $this->session->set_flashdata('message', 'Biodata Berhasil Diupdate. Silahkan login kembali untuk memperbaharui profile.');

            redirect(base_url('user/profile'));
        }
    }

    public function change_password()
    {
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $this->form_validation->set_rules('new_password', 'Password Baru', 'required|trim', [
            'required' => 'Password baru tidak boleh kosong.',
        ]);
        $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|trim|matches[new_password]', [
            'required' => 'Konfirmasi password tidak boleh kosong.',
            'matches'  => 'Konfirmasi password tidak sesuai'
        ]);

        if ($this->form_validation->run() == FALSE) {
            $data['title']        = 'Ubah Password';
            $data['page']         = 'user/profile/password';

            $this->load->view('templates/app', $data);
        } else {
            $id = $this->session->userdata('id_users');

            $data = [
                'password' => password_hash($this->input->post('new_password'), PASSWORD_DEFAULT),
            ];

            $this->user->updatePassword($id, $data);
            $this->session->set_flashdata('message', 'Password berhasil diupdate.');

            redirect(base_url('user'));
        }
    }
}
