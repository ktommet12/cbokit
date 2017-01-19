<?php
class Paginator{
    private $pageNum = 0;
    private $numPages = 0;
    private $db = null;
    const RECORDS_PER_PAGE = 10;

    

    public function __construct(){
        $this->db = DB::getInstance();
        $this->pageNum = (Input::Get('page')) ? (int)Input::Get('page') : 1;
        self::calculateNumPages();
    }
    //retrieves the posts from the database starting at @param start and going @param limit
    public function retrievePosts(){
        $start = ($this->pageNum * self::RECORDS_PER_PAGE) - self::RECORDS_PER_PAGE;
        $this->db->retrievePosts($start, self::RECORDS_PER_PAGE);
        return $this->db->getResults();
    }
    private function calculateNumPages(){
        //retrieves the total number of records in the database
        $tempNumRows = ($this->db->getNumRows('posts')->getResults()[0]->total);

        //then takes that number and calculates the number of pages based on how many records per page
        $this->numPages = ceil($tempNumRows / self::RECORDS_PER_PAGE);
    }
    public function getNumPages(){
        return $this->numPages;
    }
    public function addPageNumbers(){
        if(self::getNumPages() > 1){
            echo "<div class='text-center'>";
                echo "<nav><ul class='pagination'>";
                    if($this->pageNum == 1)
                        echo "<li class='disabled'><a aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
                    else
                        echo "<li><a href=home.php?page=".($this->pageNum-1)." aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
                    for($i = 1; $i <= $this->numPages; $i++){
                        if($i == $this->pageNum){
                            echo "<li><a><strong>".$i."</strong></a></li>";
                        }
                        else{
                            if($this->pageNum > $i){
                                echo "<li><a href=home.php?page=".($this->pageNum-1).">".$i."</a></li>";
                            }else{
                                echo "<li><a href=home.php?page=".($this->pageNum+1).">".$i."</a></li>";
                            }
                            
                        }
                    }
                    if($this->pageNum == $this->numPages)
                        echo "<li class='disabled'><a href='#' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
                    else{
                        echo "<li><a href=home.php?page=".($this->pageNum+1)." aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
                    }
                       
                    echo "";
                echo "</ul></nav>";
            echo "</div>";
        }
        else echo "";
    }
}