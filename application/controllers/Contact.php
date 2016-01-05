<?php
/**
 * contact form controller
 * @author Marie-Lyse Briffaud
 * @date 28/07/2015
 * @link http://www.kodingmadesimple.com/2015/03/codeigniter-contact-form-tutorial-email.html
 * configuration is initialized in /application/config/email.php
 * @link http://www.codeigniter.com/user_guide/libraries/email.html#setting-email-preferences
 * 
 */
class contact extends CI_Controller
{
    /**
     * @author Marie-Lyse Briffaud
     * @date 28/07/2015
     * !! to use session, set $config['sess_save_path'] in application/config/config.php to a writable directory
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->helper(array('form','url'));
        $this->load->library(array('session', 'form_validation', 'email'));

    }

    /**
     * @author Marie-Lyse Briffaud
     * @date 28/07/2015
     * display the form and send the message by email if the fields are valids
     */
    function index()
    {

        /**
         * set validation rules 
         * @link http://www.codeigniter.com/user_guide/libraries/form_validation.html?highlight=set_rules#class-reference
         * set_rules($field[, $label = ''[, $rules = '']])
         */
        $this->form_validation->set_rules('name', '', 'trim|required|callback_alpha_space_only');
        $this->form_validation->set_rules('email', '', 'trim|required|valid_email');
        $this->form_validation->set_rules('subject', '', 'trim|required');
        $this->form_validation->set_rules('message', '', 'trim|required');

        //run validation on form input
        if ($this->form_validation->run() == FALSE)
        {
            $data['logo'] = 'LOGO_GENERIQUE.png';
            $data['css'] = 'contact.css';

            //validation fails : reload the form
            $this->load->view('templates/header', $data);
            $this->load->view('contact/form_view', $data);
            $this->load->view('templates/colonneG', $data);
            $this->load->view('templates/colonneD', $data);
            $this->load->view('templates/footer', $data);
        }
        else
        {
            //get the form data
            $name =         $this->input->post('name');
            $from_email =   $this->input->post('email');
            $subject =      $this->input->post('subject');
            $message =      $this->input->post('message');

            //set to_email id to which you want to receive mails
            $to_email = 'contact-web-1@shakertechnologies.com';                      

            //send mail
            $this->email->from($from_email, $name);
            $this->email->to($to_email);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send()) {
                // mail sent
                $this->session->set_flashdata('msg','<div class="alert alert-success text-center">Votre message a été envoyé !</div>');
                redirect('contact');
            }
            else {
                //error
                $this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Echec de l\'envoi, veuillez réessayer plus tard !</div>');
                redirect('contact');
            }
        }
    }
    
    //custom validation function to accept only alphabets, dash (-) and space input
    function alpha_space_only($str)
    {
        if (!preg_match("/^[a-zA-Z- ]+$/",$str))
        {
            $this->form_validation->set_message('alpha_space_only', 'Le champs %s ne doit contenir que des lettres, tiret (-) et espace.');
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
}
?>
