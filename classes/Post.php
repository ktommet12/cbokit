<?php
class Post{
    private $postMonth          = null;     //the month the post was made
    private $postYear           = null;     //the year the post was made
    private $postDay            = null;     //the day the post was made
    private $postDate           = null;     //stores the post date as a string
    private $postTitle          = '';       //stores the title of the current post
    private $postMessage        = '';       //the post message body
    private $postersUsername    = '';       //the posters usersname
    private $postersIdNum       = null;     //stores the id num of the user who made the post
    private $postHashTags;                  //the hashtags associated with the post
    private $anonyPost          = false;    //if the post was posted anonymously
    private $postIdNum          = null;     //stores the id of the post in the db for faster lookup
    private $numComments        = 0;        //keeps track of the total number of comments that have been posted for this posts
    private $numLikes           = 0;        //stores how many like the current post has

    public function __construct($post){
        $this->postDate         = date_create($post->time_stamp);
        $this->postIdNum        = $post->post_id;
        $this->postersIdNum     = $post->user_id;
        $this->postHashTags     = $post->tag_id;
        $this->postMessage      = $post->init_text;
        $this->postTitle        = $post->title;
        $this->numComments      = $post->comments;
        $this->postYear         = self::getYear();
        $this->postDay          = self::getDay();
        $this->anonyPost        = boolval($post->anonymous);
        self::setMonth();       //converts the month to a string representation of the month
        self::setUsername();    //gets the posters username from the db
    }
    function displayDate(){
        return $this->postMonth. " " . $this->postDay . ", " . $this->postYear;
    }
    function getTime(){
        return '';
    }
    private function setUsername(){
        if($this->anonyPost){
            $this->postersUsername = 'anonymous';
        }else{
            //getting username from the database
            $test = DB::getInstance()->retrieveSpecificRecord('users', 'user_name', 'user_id', $this->postersIdNum);
            //setting the object variable for who posted to the returned value from the database
            $this->postersUsername = DB::getInstance()->getFirstResultRecord()->user_name;
        }
    }
    public function getPostersIdNum(){
        return $this->postersIdNum;
    }
    public function getPostTitle(){
        return $this->postTitle;
    }
    public function getPostID(){
        return $this->postIdNum;
    }
    public function getPostBody(){
        return $this->postMessage;
    }
    public function getUsername(){
        return $this->postersUsername;
    }
    public function getNumComments(){
        return $this->numComments;
    }
    public function getNumLikes(){
        return $this->numLikes;
    }
    function getDay(){
        return date_format(self::getPostDate(), 'd');
    }
    function getYear(){
        return date_format(self::getPostDate(), 'Y');
    }
    private function getHashTagId(){
        return $this->postHashTags;
    }
    function getHashTags(){
        DB::getInstance()->retrieveTags([$this->getHashTagId()]);
        $hashTag = DB::getInstance()->getFirstResultRecord()->hash_tag;

        return "<a href='home.php?filterByTag=$hashTag' class='hash-tag-link'>#$hashTag</a>";
    }
    function getPostDate(){
        return $this->postDate;
    }
    private function setMonth(){
        $tempMonth = date_format($this->postDate, 'm');
        $months = Calendar::getMonths();
        $this->postMonth = $months[($tempMonth*1)-1];
    }
    //$posts is an array of all the posts that are to be displayed to the screen, $username is the currently logged in person
    //this is used so that the delete button is only display if the username matches the person who originally made the post.
    static function displayPosts($posts = array(), $username){
        $numPosts = count($posts);
        if($numPosts != 0){
            for($i = 0; $i < $numPosts; $i++){
                echo "<div class='post'>";
                    if($posts[$i]->getUsername() == $username)
                        echo '<button onclick="deletePost(this)" class="delete-post"><span class="delete-post"><i class="fa fa-trash fa-lg" aria-hidden="true"></i></span></button>';
                    echo "<p>Posted - <span id='time-posted'>".$posts[$i]->displayDate()."</span> by <strong><span id='username'>".$posts[$i]->getUsername()."</span></strong></p>";
                    echo "<p class='post-title'>".$posts[$i]->getPostTitle()."</a></p>";
                    echo "<p class='post-body'>".$posts[$i]->getPostBody()."</p>";
                    echo "<p class='post-tag'>Tags: ".$posts[$i]->getHashTags()." <span class='post-comments'><i class='fa fa-comments' aria-hidden='true'></i> ".$posts[$i]->getNumComments()."<span><span class='post-likes'><i class='fa fa-thumbs-o-up' aria-hidden='true'></i> ".$posts[$i]->getNumLikes()."</span></p>";
                echo "</div>";    
            }
        } else{
            echo "no posts to display";
        }
    }
    static function getSpecificPost($id){
        $temp = DB::getInstance()->retrieveRecord('posts', 'post_id', $id);
    }
}