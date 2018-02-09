<?php
namespace console\controllers;

use common\models\dao\ImageDao;
use common\models\dao\PostsDao;
use common\models\dao\PostsKeywordDao;
use common\models\Posts;
use common\utils\simple_html_dom;
use yii\console\Controller;
class UpdateImageController extends Controller
{

    public function actionPostsCount(){

        $result = \Yii::$app->db->createCommand("select * from posts ")->queryAll();
        foreach ($result as $value){
           $result =  PostsDao::updateImageCount($value['id']);
           echo $result."\n";
        }
    }
    public function actionRecommend(){

        $sql = "delete from posts_keyword where keyword_id = 8";
        \Yii::$app->db->createCommand($sql)->execute();
        $i = 0;
        while (true){
            if($i == 10){
                break;
            }
            $rand = rand(1,25994);
            $posts = PostsDao::getById($rand);
            if($posts){
                PostsKeywordDao::addPostsImage($rand, 8);
                $i++;
            }
        }
    }
    public function actionCarousel(){
        $sql = "delete from posts_keyword where keyword_id = 7";
        \Yii::$app->db->createCommand($sql)->execute();
        $i = 0;
        while (true){
            if($i == 3){
                break;
            }
            $rand = rand(1,25994);
            $posts = PostsDao::getById($rand);
            if($posts){
                PostsKeywordDao::addPostsImage($rand, 7);
                $i++;
            }
        }
    }
    public function actionIcon(){
        $sql = "SELECT p.id from posts  as p LEFT JOIN image as i  on i.id = p.icon_id where i.id is NULL";
       $result =  \Yii::$app->db->createCommand($sql)->queryAll();
       foreach ($result as $value){
           $images = ImageDao::getByPostsId($value['id']);
           echo $images[0]->id."\n";
           PostsDao::addIcon($value['id'],$images[0]->id);
       }
    }
}