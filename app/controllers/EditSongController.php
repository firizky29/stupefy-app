<?php
    require_once __DIR__ . '/../models/Album.php';
    require_once __DIR__ . '/../models/Song.php';

    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        if($_POST['Update']){
            $song_file_directory = "./../../storage//";
            $thumbnail_directory = "./../../storage/thumbnail//";
            if($_FILES['thumbnail-image']['name'] != ""){
                $thumbnail_name = str_replace(" ", "_", $_FILES['thumbnail-image']['name']);
                $thumbnail_path = $thumbnail_directory . $thumbnail_name; 
                $i=1;
                while(file_exists($thumbnail_path)){
                    $thumbnail_path = $thumbnail_directory ."($i)".$thumbnail_name;
                    $i++;
                }
                if(!move_uploaded_file(str_replace(' ', '_', $_FILES['thumbnail-image']['tmp_name']), $thumbnail_path)){
                    echo json_encode(['status' => 'thumbnail-error', 'message' => 'Failed to upload thumbnail file']);
                    return;
                }
            } else{
                $thumbnail_path = null;
            }
            
            
            if($_FILES['song-file']['name'] != ""){
                $song_file_name = str_replace(" ", "_", $_FILES['song-file']['name']);
                $song_file_path = $song_file_directory . $song_file_name;
                $i=1;
                while(file_exists($song_file_path)){
                    $song_file_path = $song_file_directory."($i)".$song_file_name;
                    $i++;
                }
                if(!move_uploaded_file(str_replace(' ', '_', $_FILES['song-file']['tmp_name']), $song_file_path)){
                    echo json_encode(['status' => 'thumbnail-error', 'message' => 'Failed to upload audio file']);
                    return;
                }
            }else{
                $song_file_path = null;
            }
            
            $song = new Song();
            
            $songID = intval($_POST["song-id"]);
            $songName = $_POST["song-title"];
            $songArtist = $_POST["song-artist"];
            $songReleaseDate = date('Y-m-d', strtotime($_POST["release-date"]));
            $songGenre = $_POST["song-genre"] or NULL;
            
            $old_song = $song->getByID($songID);
            $old_image_path = $old_song['Image_path'];
            $old_audio_path = $old_song['Audio_path'];
            if(!isset($thumbnail_path)){
                $thumbnail_path = $old_image_path;
            }
            if(!isset($song_file_path)){
                $song_file_path = $old_audio_path;
            }
    
            $song->updateSong($songName, $songArtist, $songReleaseDate, $songGenre, $songID, $thumbnail_path, $song_file_path);
    
            
    
            echo json_encode(['status' => 'success', 'message' => 'Song updated successfully']);
        }
    }
?>