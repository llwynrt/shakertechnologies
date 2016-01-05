<?php

class Pages extends CI_Controller {

    /**
     * @author Marie-Lyse Briffaud
     * @date 28/07/2015
     * 
     */
	/*public function index(){

        $data['logo'] = 'LOGO_GENERIQUE.png'; 

        $this->load->view('templates/header', $data);
        $this->load->view('templates/colonneG', $data);
        $this->load->view('pages/index', $data);
        $this->load->view('templates/colonneD', $data);

        $this->load->view('templates/footer', $data);
	}*/

    /**
     * @author Marie-Lyse Briffaud
     * @date 28/07/2015
     * @param $pages : name of the page to display
     * 
     */    
	public function view($page = 'index'){

        if ( ! file_exists(APPPATH.'/views/pages/'.$page.'.php')){
                // Whoops, we don't have a page for that!
                show_404();
        }

        //choose the logo to display
        if (file_exists(APPPATH.'../assets/img/LOGO_'.strtoupper($page).'.png')){
            $data['logo'] = 'LOGO_'.strtoupper($page).'.png';
        }
        else {
            $data['logo'] = 'LOGO_GENERIQUE.png';
        }
        //custom css file to load
        if (file_exists(APPPATH.'../assets/css/'.$page.'.css')){
            $data['css'] = $page.'.css';
        }

 
        
        $this->load->view('templates/header', $data);
        $this->load->view('pages/'.$page, $data);
        $this->load->view('templates/colonneG', $data);
        $this->load->view('templates/colonneD', $data);
        $this->load->view('templates/footer', $data);
	}
}
?>
