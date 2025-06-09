<?php
if(!class_exists('mySQL_ORM'))
require __DIR__ . '/mySQL_ORM.php';
require __DIR__ . '/../config.php';
class Post extends mySQL_ORM {
    private $table = 'blogpost';

    /**
     * Constructor
     */

    public function __construct()
    {
        global $dbconfig;
        parent::__construct($dbconfig);
    }

    /**
     * get all posts' data
     * @return array an array of all posts' data
     */

    public function getPosts(){
        $this->select($this->table,'users.id as writerid,title,content,date_created,' . $this->table . '.id','','','','','users',$this->table.'.writer = users.id','name');
        return $this->fetchAll();
    }

    /**
     * get all posts' data for specific user
     * @param int $userId the ID for the desired user
     * @return array an array of all posts' data
     */

    public function getUserPosts($userId){
        $this->select($this->table,'users.id as writerid,title,content,date_created,' . $this->table . '.id','users.id=' . $userId,'','','','users',$this->table.'.writer = users.id','name');
        return $this->fetchAll();
    }

    /**
     * get the data of one post
     * @param int $postId the ID of the desired post
     * @return array associative array of the post's data
     */

    public function getPost($postId){
        $this->select($this->table,'users.id as writerid,title,content,date_created,' . $this->table . '.id',$this->table .'.id = :id','','','','users',$this->table.'.writer = users.id','name',['id' => $postId]);
        return $this->fetch();
    }

    /**
     * add new post
     * @param array $post the data of the post (title="title",content="content",...)
     * @return int new row ID
     */

    public function addPost($post){
        return $this->insert($this->table,$post);
    }

    /**
     * delete one post
     * @param int $postId the ID of the post to be deleted
     * @return int number of affected rows
     */

    public function deletePost($postId){
        if(is_numeric($postId))
            return $this->delete($this->table,'id = :id', ['id' => $postId]);
    }

    /**
     * update post data
     * @param int $postId the ID of the post to be updated
     * @param array $post the data of the post (title="title",content="content",...)
     * @return int number of affected rows
     */

    public function updatePost($postId, $post) {
        return $this->update($this->table, $post,'id =  :id', ['id' => $postId]);
    }

    /**
     * search for post by title or content
     * @param string @keyword words to search in the title or content
     * @return array an array of all matched posts
     */

    public function searchPosts($keyword) {
        $this->select($this->table, 'users.id as writerid,title,content,date_created,' . $this->table . '.id', " title LIKE :keyword OR content LIKE :keyword ",'','','','users',$this->table.'.writer = users.id','name',[':keyword' => "%$keyword%"]);
        return $this->fetchAll();
    }

    /**
     * search for post by title or content from specific user
     * @param int $userId the ID of the desired user
     * @param string @keyword words to search in the title or content
     * @return array an array of all matched posts
     */

    public function searchUserPosts($userId,$keyword){
        $this->select($this->table,'users.id as writerid,title,content,date_created,' . $this->table . '.id','users.id= :id AND (title LIKE :keyword OR content LIKE :keyword) ','','','','users',$this->table.'.writer = users.id','name',['id' => $userId, ':keyword' => "%$keyword%"]);
        return $this->fetchAll();
    }

}

?>