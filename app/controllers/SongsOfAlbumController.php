<?php
    require_once __DIR__ . '/../models/Song.php';

    function createEntry($song, $i){
        if(!isset($song['Penyanyi'])){
            $song['Penyanyi'] = 'Unknown';
        }
        if(!isset($song['Genre'])){
            $song['Genre'] = '-';
        }

        $id = $song['song_id'];

        $html = <<<"EOT"
            <tbody class="content-entry" onclick = 'getDetailedSong($id)'>
                <tr>
                    <td class = 'content-id' rowspan='2'> 
                        $i 
                    </td>
                    <td class = 'content-img-container' rowspan='2'>
                        <img src = $song[Image_path] class='content-img'>
                    </td>
                    <td class = 'content-title'>
                        $song[Judul]
                    </td>
                    <td class = 'content-genre' rowspan='2'>
                        $song[Genre]
                    </td>
                </tr>
                <tr>
                    <td class = 'content-artist'>
                        $song[Penyanyi]
                    </td>
                </tr>
            </tbody>
        EOT;


        return $html;
    }

    $songs = new Song();
    $songs = $songs->getByAlbumID($_GET['id']);
    $cards = '';
    $i = 1;
    foreach ($songs as $song) {
        $cards .= createEntry($song, $i);
        $i++;
    }

    echo json_encode([$cards]);
?>