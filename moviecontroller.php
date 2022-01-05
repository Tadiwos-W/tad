<?php
include_once "constructer.php";
class Moviecontroller{
    private $crud;
    public function __construct(){
        $this->crud= new Crud();
    }
    public function addmovie(){
        $movie_data=['mv_title'=>$_POST['mv_title'],
        'mv_year_released'=>$_POST['mv_year_released'],];
        $movie_id=$this->crud->create($movie_data,'movies');
        $movie_genres=isset($_POST['genres'])?$_POST['genres'] :"";
        $this->createe($movie_genres,$movie_id);
        $this->saveAndUploadCoverImage($movie_id);
    }
    public function createe($movie_gen,$movie_id){
        foreach($movie_gen as  $genre_id ){
            //$movie_genres=$this->crud->read("SELECT * FROM mv_genres WHERE mvg_ref_movie=$movie_id and mvg_ref_genre=$genre_id ");
            if(empty($movie_gen)){
                $movie_genres_arr=[
                    'mvg_ref_genre'=>$genre_id,
                    'mvg_ref_movie' =>$movie_id
                ];
                    $this->crud->create($movie_genres_arr,'mv_genres');
            }

            
        }
    }
    public function getMovie(){
        $query="SELECT mv_id, mv_title,img_path,gnr_name,GROUP_CONCAT(gnr_name) genres,mv_year_released
        FROM movies
        LEFT JOIN mv_genres on mvg_ref_movie=mv_id 
        LEFT  JOIN genres on mvg_ref_genre =gnr_id 
        LEFT JOIN images on img_ref_movie=mv_id GROUP BY mv_id  ORDER BY mv_id DESC ";
        $result=$this->crud->read($query);
        return $result;
    }
    public function getMovies($mv_id){
        $query="SELECT mv_id, mv_title,img_path,gnr_name,GROUP_CONCAT(gnr_name) genres,mv_year_released
        FROM movies
        LEFT JOIN mv_genres on mvg_ref_movie=mv_id 
        LEFT  JOIN genres on mvg_ref_genre =gnr_id 
        LEFT JOIN images on img_ref_movie=mv_id 
        WHERE mv_id=$mv_id 
        GROUP BY mv_id  ORDER BY mv_id DESC ";
        $result=$this->crud->read($query);
        return $result;
    }
    public function saveAndUploadCoverImage($movie_id){
        $dir ="../images/movie_covers/movie_$movie_id";
        if (!file_exists($dir)){
            mkdir($dir, 0777,true);
        }
        $dir=$dir."/".basename($_FILES["cover_image"]["name"]);
        move_uploaded_file($_FILES["cover_image"]["tmp_name"],$dir);
        $image_info=['img_path'=>str_replace('../','',$dir),
        'img_ref_movie'=>$movie_id];
        $this->crud->create($image_info,'images');
        }
    public function editMovie($movie_id){
        $year_released=$_POST['mv_year_released'];
        $mv_title=$_POST['mv_title'];
        $sql=" UPDATE movies set mv_year_released='$year_released',mv_title='$mv_title'
        where mv_id='$movie_id'";
        $this->crud->update($sql); 
        $this->createe($_POST['genres'],$movie_id);
        $this->deleteDeselectedGenres($movie_id);
        if(!empty($_FILES['cover_image']['name'])){
            $this->crud->delete("delete from images whre img_ref_movie= $movie_id");
            $this->saveAndUploadCoverImage($movie_id);
        }

    }
   
    public function deleteDeselectedGenres($movie_id){
        $movie_genres=$this->crud->read("SELECT * FROM mv_genres where mvg_ref_movie=$movie_id");
        foreach($movie_genres as $key => $movie_genre){
            $genre_id=$movie_genre['mvg_ref_genre'];
            if(!in_array($genre_id,$_POST['genres']))
            $this->crud->delete("delete from mv_genres where mvg_ref_genre=$genre_id");
                
        }
    }
    

    }

?>
