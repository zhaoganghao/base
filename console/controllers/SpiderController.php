<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace console\controllers;
use common\utils\Snoopy;
use yii\console\Controller;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class SpiderController extends Controller
{

    public  function actionSnoopy()
    {
        $snoopy = new Snoopy;


        $snoopy->fetchlinks("http://www.phpbuilder.com/");
        var_dump($snoopy->results) ;
    }
    public  function actionCrawl()
    {
        require_once(\Yii::getAlias('@common')."/PHPCrawl_083/libs/PHPCrawler.class.php");
        $crawler = new \PHPCrawler();
    }
    public  function actionPhpQuery()
    {
        include(\Yii::getAlias('@common')."/phpQuery/phpQuery/phpQuery.php");
        $doc = \PhpQuery::newDocument('<div/>');

        // FILL IT
        // array syntax works like ->find() here
                $doc['div']->append('<ul></ul>');
        // array set changes inner html
                $doc['div ul'] = '<li>1</li> <li>2</li> <li>3</li>';

        // MANIPULATE IT
                $li = null;
        // almost everything can be a chain
                $doc['ul > li']
                    ->addClass('my-new-class')
                    ->filter(':last')
                    ->addClass('last-li')
        // save it anywhere in the chain
                    ->toReference($li);

        // SELECT DOCUMENT
        // pq(); is using selected document as default
        \phpQuery::selectDocument($doc);
        // documents are selected when created or by above method
        // query all unordered lists in last selected document
                $ul = pq('ul')->insertAfter('div');

        // ITERATE IT
        // all direct LIs from $ul
                foreach($ul['> li'] as $li) {
                    // iteration returns PLAIN dom nodes, NOT phpQuery objects
                    $tagName = $li->tagName;
                    $childNodes = $li->childNodes;
                    // so you NEED to wrap it within phpQuery, using pq();
                    pq($li)->addClass('my-second-new-class');
                }

        // PRINT OUTPUT
        // 1st way
                print \phpQuery::getDocument($doc->getDocumentID());
        // 2nd way
                print \phpQuery::getDocument(pq('div')->getDocumentID());
        // 3rd way
                print pq('div')->getDocument();
        // 4th way
                print $doc->htmlOuter();
        // 5th way
                print $doc;
        // another...
        print $doc['ul'];
    }
}
