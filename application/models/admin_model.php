<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of admin_model
 *
 * @author syndrome
 */
class admin_model extends CI_Model {
    
    function login()
    {
        // Set the language as set in config/config.php
        $this->load->helper('language');
        $this->lang->load('auth');
        $this->load->library('form_validation');

        // Set validation rules.
        $this->form_validation->set_rules('login_identity', lang('identity'), 'required');
        $this->form_validation->set_rules('login_password', lang('password'), 'required');

        // Run the validation.
        if ($this->form_validation->run())
        {
           $identity = $this->input->post('login_identity');
           $password = $this->input->post('login_password');

           /*$sql_select = array(
                $this->auth->primary_identity_col, 
                $this->auth->tbl_col_user_account['id'], 
                $this->auth->tbl_col_user_account['password'], 
                $this->auth->tbl_col_user_account['group_id'], 
                $this->auth->tbl_col_user_account['activation_token'], 
                $this->auth->tbl_col_user_account['active'], 
                $this->auth->tbl_col_user_account['suspend'], 
                $this->auth->tbl_col_user_account['last_login_date'], 
                $this->auth->tbl_col_user_account['failed_logins']
            );*/

           $sql_where = "uacc_email = '".$identity."' OR uacc_username = '".$identity."'";

           $query = $this->db->select("*")
                ->from("user_accounts")
                ->where($sql_where);

           
           
           if ($query->num_rows() == 1)
            {	
                $user = $query->row();
            }
var_dump($user);
            if ($this->verify_password($identity, $password))
            {
                echo "this is good.";
                // Log the user to his account
                $session_user = array('username'  => $dec_login,'email' => $dec_mail,
                                    'is_logged_in' => TRUE, 'is_admin'  => TRUE);
                $this->session->set_userdata($session_user);
            }
            else
            {
                echo "This is wrong.";
            }
        }
        else
        {	
                // Set validation errors.
                $this->data['message'] = validation_errors('<p class="error_msg">', '</p>');

                return FALSE;
        }
    }
        
    public function verify_password($identity, $password)
    {
        $sql_select = array(
            $this->auth->tbl_col_user_account['password'],
            $this->auth->tbl_col_user_account['salt']
        );

        $query = $this->db->select($sql_select)
                ->where($this->auth->primary_identity_col, $identity)
                ->limit(1)
                ->get($this->auth->tbl_user_account);

        $result = $query->row();

        if ($query->num_rows() !== 1)
        {
                    return FALSE;
        }

        $database_password = $result->{$this->auth->database_config['user_acc']['columns']['password']};
        $database_salt = $result->{$this->auth->database_config['user_acc']['columns']['salt']};
        $static_salt = $this->auth->auth_security['static_salt'];

        require_once(APPPATH.'libraries/phpass/PasswordHash.php');				
        $hash_token = new PasswordHash(8, FALSE);

        return $hash_token->CheckPassword($database_salt . $verify_password . $static_salt, $database_password);
    }
}