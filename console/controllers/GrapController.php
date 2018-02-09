<?php
namespace console\controllers;

use common\models\dao\ImageDao;
use common\models\dao\PostsDao;
use common\utils\simple_html_dom;
use yii\console\Controller;
class GrapController extends Controller
{
    private function downloadImage($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $file = curl_exec($ch);
        curl_close($ch);
        return $file;

    }
    public function actionDelete()
    {

    }
    public function actionDownload()
    {
        $result = \Yii::$app->db->createCommand("select * from image where file_name =''  ")->queryAll();
        $path = "/home/work/resource/beauty/";
        foreach ($result as $key => $value){

            $url = $value['remote_url'];
            $file = $this->downloadImage($url);
            $md5  = md5($file);
            $imageModel = ImageDao::getByFileMd5($md5);
            if($imageModel){
                echo "图片已经存在{$value['id']}\n";
                $result = ImageDao::updateData($value['id'], $imageModel->file_name, $imageModel->image_ext,
                    $imageModel->image_width,$imageModel->image_height,$imageModel->file_size, $imageModel->file_md5);
                if($result){
                    echo "图片更新成功{$value['id']}\n";
                }else{
                    echo "图片更新失败{$value['id']}\n";
                }
                continue;
            }

            $ext = substr($url,strripos($url,'.')+1);
            $filename = $md5.".".$ext;
            $resource = fopen($path . $filename, 'a');
            fwrite($resource, $file);
            unset($file);
            fclose($resource);
            try{
                list($width, $height, $type, $attr) = getimagesize($path . $filename);
                if(!$width){
                    echo "图片下载有问题{$value['id']}\n";
                    continue;
                }
                $size = filesize($path . $filename);
                $result = ImageDao::updateData($value['id'], $filename, $ext, $width,$height,$size, $md5);
                if($result){
                    echo "图片更新成功{$value['id']}\n";
                }else{
                    echo "图片更新失败{$value['id']}\n";
                }
            }catch (\Exception $e){
                echo "图片更新失败Exception{$value['id']}\n";
            }


        }

    }
    public function actionCategory(){
        $commonPath = \Yii::getAlias("@common");
        require("$commonPath/utils/simple_html_dom.php");
        for($i =2 ; $i <17; $i++){
            echo "category: ".$i . "\n";
            $j = 0;
            while(true){
                if($j == 0){
                    $url = "http://a.gqtp.com/list_{$i}.html";
                }else{
                    $url = "http://a.gqtp.com/list_{$i}_{$j}.html";
                }
                $html = $this->file_get_html($url);
                $ulTag = $html->find('.comic_list li');
                if(!$ulTag){
                    break;
                }
                foreach ($ulTag as $element){
                    $aTag = $element->find('a');
                    if(!$aTag){
                        break;
                    }
                    $a = $aTag[0];
                    $postsIdStr = substr($a->href,strripos($a->href,'/')+1);
                    $postsId = substr($postsIdStr,0,strripos($postsIdStr,'.'));
                    $result = PostsDao::updateCategory($postsId, $i);
                    if($result){
                        echo "success:".$postsId . "\n";
                    }else{
                        echo "failed:".$postsId . "\n";
                    }
                }
                unset($html);
                $j++;
            }
        }

    }
    public function actionGqtp(){
        error_reporting(0);
        $commonPath = \Yii::getAlias("@common");
        $pattern="/(jpg|png|gif)$/";
        require("$commonPath/utils/simple_html_dom.php");
        for($i =8236 ; $i <26787; $i++){
            $postId = 0;
            $j = 0;
            echo "html i:{$i}\n";
            while(true){
                if($j == 0){
                    $url = "http://a.gqtp.com/{$i}.html";
                }else{
                    $url = "http://a.gqtp.com/{$i}_{$j}.html";
                }
                if($j != 1){
                    $html = $this->file_get_html($url);
                    if($j == 0){
                        $titleTag = $html->find('#main h1 strong');
                        if($titleTag){
                            $element = $titleTag[0];
                            $postModel =  PostsDao::getByRefId($i);
                            if($postModel){
                                $postId = $postModel->id;
                                echo "创建posts已经存在id:".$postId . "\n";
                                break;
                            }
                            $title = $element->text();
                            $title = $title ? $title : $i;
                            $postModel = PostsDao::createOrUpdate($title, "", null, $i);
                            if($postModel){
                                echo "创建posts成功:".$postModel->id . "\n";
                            }else{
                                echo "创建posts失败ref_id:".$i . "\n";
                            }
                            $postId = $postModel->id;
                        }
                    }
                    $imageTag = $html->find('#imgString img');
                    if(!$imageTag){
                        break;
                    }
                    $element = $imageTag[0];
                    if(strlen($element->src) < 28 || !preg_match($pattern,$element->src)){
                        break;
                    }
                    if(!$postId){
                        break;
                    }
                    $imageUrl = $element->src;
                    $imageModel =ImageDao::createOrUpdate($imageUrl, $j, $postId);
                    if($imageModel){
                        if($j == 0){
                            PostsDao::addIcon($postId, $imageModel->id);
                        }
                        echo "创建images成功:".$imageModel->id . "\n";
                    }else{
                        echo "创建images失败ref_id:".$j . "\n";
                    }

                    unset($html);
                }

                $j++;
            }
        }

    }
    private function file_get_html($url, $use_include_path = false, $context=null, $offset = -1, $maxLen=-1, $lowercase = true, $forceTagsClosed=true, $target_charset = DEFAULT_TARGET_CHARSET, $stripRN=true, $defaultBRText=DEFAULT_BR_TEXT, $defaultSpanText=DEFAULT_SPAN_TEXT)
    {
        // We DO force the tags to be terminated.
        $dom = new simple_html_dom(null, $lowercase, $forceTagsClosed, $target_charset, $stripRN, $defaultBRText, $defaultSpanText);
        // For sourceforge users: uncomment the next line and comment the retreive_url_contents line 2 lines down if it is not already done.
        $contents = file_get_contents($url);
        // Paperg - use our own mechanism for getting the contents as we want to control the timeout.
        //$contents = retrieve_url_contents($url);
        if (empty($contents) || strlen($contents) > MAX_FILE_SIZE)
        {
            return false;
        }
        // The second parameter can force the selectors to all be lowercase.
        $dom->load($contents, $lowercase, $stripRN);
        return $dom;
    }
}