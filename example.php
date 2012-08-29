<?php

require_once("lib/PHPGravatar.php");

$tag_attr = array(
    "border" => "0",
    "style" => "border: 1px solid red;"
);

$gravatar = new PHPGravatar('marco.germani.developer@gmail.com');
$gravatar->setSize(100);
$gravatar->setImageset("404");
$gravatar->setIsTag(true);
$gravatar->setImgTagAttr($tag_attr);

if (!$gravatar->isError()) {

    echo $gravatar->buildGravatar();
    
} else {

    echo "Error to get gravatar: {$gravatar->getError()}";
}

?>