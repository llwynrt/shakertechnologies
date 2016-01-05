<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Service layer : data persistance and business logic
 * 
 * @author Mickael Ruau
 * @date 10/21/2014
 * @link http://codeigniter.com/user_guide/tutorial/news_section.html
 * @since 0.2 This version uses ORM so that it can be easily used with any sgbd
 * 
 * Red Flags: My Model architecture might be going bad if:
 * - The Model contains session logic.
 * - The Value Objects retain (ie. store) references to Service objects or Gateway objects.
 * @link http://www.bennadel.com/blog/2379-a-better-understanding-of-mvc-model-view-controller-thanks-to-steven-neiland.htm
 * 
 * @todo faire tuto pour sql lite (cf hébergements sans mysql)
 * @todo suivre livre green web
 */
class Topics_model extends CI_Model {
    
    static protected $topics_cache = NULL;//array caching the result of the db query
    
    
    /**
    * Get paths to build links for assets
    * 
    * @author Mickael Ruau
    * @date 10/24/2014
    * @since 0.2
    * 
    * @return array base paths for slideshow elements
    */
    
    public function get_paths() {
        
        $this->load->helper('url');
        
        $assets_root    = base_url() . 'assets/';
        $topics_root    = site_url() . '/topics/'; 
        
        $uploads_root   = base_url() . 'uploads/';
        $img_root       = $uploads_root . 'img/';
        $js_root        = $assets_root . 'js/';
        $css_root       = $assets_root . 'css/';
        
        $slideshow_paths = array(
                                    'base_url'      => base_url(),
                                    'assets_root'   => $assets_root,
                                    'site_url'      => site_url(),            
                                    'topics_root'   => $topics_root,
                                    'uploads_root'  => $uploads_root,            
                                    'img_root'      => $img_root,
                                    'js_root'       => $js_root,
                                    'css_root'      => $css_root           
                                );
        return $slideshow_paths;
    }
    
    /**
     * @author Mickael Ruau
     * @date 10/21/2014
     * @since 0.2
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
       
    /**
     * @author Mickael Ruau
     * @date 10/21/2014
     * @since 0.2
     * 
     * @param string $id 
     *              database id of the parent topic
     * @param string $slug 
     *              slug of the parent topic
     *              NB : topics.slug : a kind of topic id (human readable)
     * 
     * @return array the topic :
     *  - NULL if not found
     */
    public function get_topic($id = NULL, $slug = NULL)
    {   
        if (!isset($id) && !isset($slug)) {
            return NULL;
        }
        
        $restrictions = array();
        
        if (!empty($id)){
            $restrictions = array('id' => $id); 
        } else if (!empty($slug)) {
            $restrictions = array('slug' => $slug); 
        }             
             
        $query = $this->db->get_where('topics', $restrictions);  
        return $query->row_array();  
        
        //@todo : sanitize this query (using ORM : do NOT use MySql ticks)
    }

    /**
     * Returns children topics
     * If no child topic exists, returns parent topic
     * 
     * @author Mickael Ruau
     * @date 10/28/2014
     * @since 0.3
     * 
     * @param int $id_parent 
     *              id of the parent topic
     * 
     * @param bool $use_cache 
     *              false will send a query to the database
     * 
     * @return array list containing the parent topic and his children
     * @todo versions sans orm (pour sql lite et pour mysql)
     */
    public function get_children_topics($id_parent = NULL)
    {   
        //@todo VERSION WITH ORM
                
        //VERSION WITHOUT ORM
        $sql_arguments = array($id_parent);
        $sql = '';
        //get the parent topic from his slug
              
        $query_parent = 'SELECT parent.id, parent.idParent, parent.slug, '
                . ' parent.wording_i18n, parent.text_i18n, parent.photopath, '
                . ' parent.photofilename, parent.showing_order '
                . ' FROM '
                . ' topics AS parent ';
        
        if(is_null($id_parent)){
           $query_parent .= ' WHERE parent.id IS NULL';
        } else {
           $query_parent .= ' WHERE parent.id = ?';
        }
                
        //$sql .= ' UNION ';
       
        //get children topics
        $sql .= ' SELECT child.id, child.idParent, child.slug, '
                . ' child.wording_i18n, child.text_i18n, child.photopath, '
                . ' child.photofilename, child.showing_order '
                . ' FROM '
                . ' topics AS child ';                
        
        if(is_null($id_parent)){
           $sql .= ' WHERE child.idParent IS NULL';
        } else {
           $sql .= ' WHERE child.idParent = ?';
        }
        //@todo : order by
 
        //parent topic will be placed first in the array
        //children topics are ordered in showing order : ready for the view!
        
        $query = $this->db->query($sql, $sql_arguments);
        $topics = $query->result_array();
        
        if (count($topics) < 1) {
            $query = $this->db->query($query_parent, $sql_arguments);
            $topics = $query->result_array();
        }
        
        return $topics;
        //@todo : sanitize this query (using ORM : do NOT use MySql ticks)
    }
    
    /**
     * Returns children topics
     * If no child topic exists, returns parent topic
     * 
     * @author Mickael Ruau
     * @date 10/21/2014
     * @since 0.2
     * 
     * @param string $slug 
     *              slug of the parent topic
     *              NB : topics.slug : a kind of topic id (human readable)
     * @param bool $use_cache 
     *              false will send a query to the database
     * 
     * @return array list containing the parent topic and his children
     * @todo versions sans orm (pour sql lite et pour mysql)
     */
    public function get_topics($slug = NULL)
    {   
        if (empty($slug)){
                $query = $this->db->get('topics');
                return $query->result_array();
        }

        //@todo VERSION WITH ORM

        //VERSION WITHOUT ORM
        $sql_arguments = array($slug);
        $sql = '';
        //get the parent topic from his slug
              
        $query_parent = 'SELECT parent.id, parent.idParent, parent.slug, '
                . ' parent.wording_i18n, parent.text_i18n, parent.photopath, '
                . ' parent.photofilename, parent.showing_order '
                . ' FROM '
                . ' topics AS parent '
                . ' WHERE parent.slug = ?';
        
        //$sql .= ' UNION ';
       
        //get children topics
        $sql .= ' SELECT child.id, child.idParent, child.slug, '
                . ' child.wording_i18n, child.text_i18n, child.photopath, '
                . ' child.photofilename, child.showing_order '
                . ' FROM '
                . ' topics AS parent ' 
                . ' LEFT JOIN '
                . ' topics AS child '
                . ' ON '
                . ' parent.id = child.idParent '
                . ' WHERE parent.slug = ? '
                . ' AND child.idParent IS NOT NULL ';
        //@todo : order by
 
        //parent topic will be placed first in the array
        //children topics are ordered in showing order : ready for the view!
        
        $query = $this->db->query($sql, $sql_arguments);
        $topics = $query->result_array();
        
        if (count($topics) < 1) {
            $query = $this->db->query($query_parent, $sql_arguments);
            $topics = $query->result_array();
        }
        
        return $topics;
        //@todo : sanitize this query (using ORM : do NOT use MySql ticks)
    }

    /** Gets topics menu, ready for list printing
     * 
     * - Topics are ordered by id 
     * - Children topics are placed in the 'children' element of their parent topic
     * 
     * The resultant array must be parsed in a recursive way
     * 
     * @author Mickael Ruau
     * @date 10/21/2014
     * @since 0.2
     * 
     * @param bool $use_cache 
     *              false will send a query to the database for each call
     * 
     * @return array list of topics
     *          (empty array if no topic found in database)
     */
    public function get_topics_menu_list()
    {
        $restrictions = array('active' => 1, 
                                'is_menu' => 1,
                                'idParent' => NULL
                            );
        
        $this->db->order_by('idParent asc, showing_order asc');      
        $query = $this->db->get_where('topics', $restrictions);      
        
        return $query->result_array();  
    }
    
    /** Gets topics submenus, ready for list printing
     * 
     * - Topics are ordered by id and showing order
     * 
     * The resultant array must be parsed in a recursive way
     * 
     * @author Arnaut Delannoy
     * @date 05/12/2014
     * @since 0.3
     * 
     * @todo Doing the same with a submenu, to allow submenus to show up when
     *              one of its item is selected.
     * 
     * @param string $slug
     *              the slug of the parent menu
     * 
     * @return array list of topics
     *          (empty array if no topic found in database)
     */
    public function get_topics_submenu_list($slug)
    {
        // Getting the ID of the parent menu with his slug
        $idParent = $this->get_topic(NULL, $slug);
        
        // Seeting the query
        $restrictions = array('active' => 1, 
                                'is_submenu' => 1,
                                'idParent' => $idParent['id']
                            );
        
        $this->db->order_by('idParent asc, showing_order asc');
        
        // Getting all the submenus of the selected parent
        $query = $this->db->get_where('topics', $restrictions);
        
        $topicsArray = $query->result_array();
        if (empty($topicsArray))
        {
            $topicsArray = $this->get_same_level_submenu_list($slug);
        }
        
        return $topicsArray;
    }
    
    /** Gets same level submenu, ready for list printing
     * 
     * - Topics are ordered by id and showing order
     * 
     * The resultant array must be parsed in a recursive way
     * 
     * @author Arnaut Delannoy
     * @date 10/12/2014
     * @since 0.3
     * 
     * 
     * @param string $slug
     *              the slug of the parent menu
     * 
     * @return array list of topics
     *          (empty array if no topic found in database)
     */
    public function get_same_level_submenu_list($slug)
    {
        // Getting the ID of the parent menu with his slug
        $topic = $this->get_topic(NULL, $slug);
        
        // Seeting the query
        $restrictions = array('active' => 1, 
                                'is_submenu' => 1,
                                'idParent' => $topic['idParent']
                            );
        
        $this->db->order_by('idParent asc, showing_order asc');
        
        // Getting all the submenus of the selected parent
        $query = $this->db->get_where('topics', $restrictions);      
        
        return $query->result_array();  
    }
    
    /** Inserts or updates a topic in database
     * 
     *  By default will insert
     *  If topic id is present and set in form, will update
     * 
     * @author Mickael Ruau
     * @date 10/21/2014
     * @since 0.2
     * @link http://codeigniter.com/user_guide/tutorial/create_news_items.html 
     * 
     * @param array $upload_data as returned by CI Helper Upload : $this->upload->data()
     * @param array $user_data map to sql fields ('wording_i18n', etc.)
     * @return bool true if success
     */
    public function set_topic($upload_data, $user_data)
    {
        //@todo securiser
        $this->load->helper('url');
        
        $data = array();
        $id = NULL;
        
        //mandatory sql fields 
        $data['slug']           = url_title($user_data['wording_i18n'], 'dash', TRUE);        
        $parent_topic           = $user_data['idParent'];
        $data['idParent']       = empty($parent_topic)? NULL : $parent_topic;
        $data['wording_i18n']   = $user_data['wording_i18n']; 
        
        //optional sql fields
        $data['text_i18n']      = $user_data['text_i18n']; 
        $data['photofilename']  = $upload_data['raw_name'] . $upload_data['file_ext'];
        $data['photopath']      = $upload_data['upload_path']; 
        $data['showing_order']  = $user_data['showing_order']; 
        $data['is_menu']        = $user_data['is_menu']; 
        $data['active']         = $user_data['active']; 
        
        //UPDATE OR INSERT
        if (isset($user_data['id']) 
                && $user_data['id'] != NULL
                && trim($user_data['id']) != ''
            ) 
        {
            
            $id = $user_data['id']; 
            $this->db->where('id', $id);           
            return $this->db->update('topics', $data);
        } 
        else 
        {
            $sql = "INSERT INTO topics (active, slug, idParent, wording_i18n, wording, text_i18n, text, photopath, photofilename, is_menu, showing_order)"
                    . "VALUES (".$data['is_menu'].", '".$data['slug']."', ";
            if ($data['idParent'] == "" || is_null($data['idParent']))
            {
                $sql.="NULL";
            }
            else
            {
                $sql.=$data['idParent'];
            }
            $sql.=", '".$data['wording_i18n']."', '', '".$data['text_i18n']."', '', '".$data['photopath']."', '".$data['photofilename']."', ".$data['is_menu'].", ".$data['showing_order'].")";
            return $this->db->query($sql);
            //return $this->db->insert('topics', $data);
        }
    }
  
}
/* End of file topics_model.php */
/* Location: ./application/models/topics_model.php */