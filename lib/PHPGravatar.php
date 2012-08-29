<?php

/**
 * PHPGravatar
 *
 * @author Marco Germani <marco.germani.developer@gmail.com>
 * @version 1.0.0.0 2012-08-29
 */

class PHPGravatar {

    private $gravatar_uri = "http://www.gravatar.com/avatar/";
    private $gravatar_signup = "https://it.gravatar.com/site/signup/";
    
    private $size_options = array(
        "min" => 1,
        "max" => 2048
    );
    
    private $imageset_options = array(
        "404",
        "mm",
        "identicon",
        "monsterid",
        "wavatar"
    );
    
    private $rating_options = array(
        "g",
        "pg",
        "r",
        "x"
    );
    
    private $email = null;
    private $size = 50;
    private $imageset = "mm";
    private $rating = "g";
    private $img_tag = false;
    private $img_tag_attr = array();
    
    private $error = array(
        'error' => false,
        'message' => "Empty message"
    );

    public function __construct($email) {

        try {

            if (empty($email)) {

                throw new Exception("Empty email");
            }

            if (!$this->isEmail($email)) {

                throw new Exception("Invalid email");
            }

            $this->email = trim($email);
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function setSize($size) {

        try {

            if (empty($size)) {

                throw new Exception("Empty size option");
            }

            if (!is_numeric($size)) {

                throw new Exception("Size option is not numeric");
            }

            if ($size < $this->size_options["min"] || $size > $this->size_options["max"]) {

                throw new Exception("Invalid size range(size must be between{$this->size_options["min"]} and {$this->size_options["max"]})");
            }

            $this->size = $size;
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function setImageset($imageset) {

        try {

            if (empty($imageset)) {

                throw new Exception("Empty imageset option");
            }

            if (!in_array($imageset, $this->imageset_options)) {

                throw new Exception("Imageset option not valid(imageset must be one of this: " . implode(", ", $this->imageset_options) . ")");
            }

            $this->imageset = $imageset;
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function setRating($rating) {

        try {

            if (empty($rating)) {

                throw new Exception("Empty rating option");
            }

            if (!in_array($rating, $this->rating_options)) {

                throw new Exception("Rating option is not numeric(rating must be one of this: " . implode(", ", $this->rating_options) . ")");
            }

            $this->rating = $rating;
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function setIsTag($is_tag) {

        try {

            if (empty($is_tag)) {

                throw new Exception("Empty is_tag option");
            }

            if (!is_bool($is_tag)) {

                throw new Exception("Option is_tag must be boolean");
            }

            $this->img_tag = $is_tag;
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function setImgTagAttr($attr) {

        try {

            if (empty($attr)) {

                throw new Exception("Empty attr option");
            }

            if (!is_array($attr)) {

                throw new Exception("Option attr must be an array");
            }

            $this->img_tag_attr = $attr;
            
        } catch (Exception $e) {

            $this->setError($e->getMessage());
        }
    }

    public function buildGravatar($label = "") {

        if ($this->isError() === false) {
            
            $url = $this->gravatar_uri;
            $url .= md5(strtolower(trim($this->email)));
            $url .= "?s={$this->size}&d={$this->imageset}&r={$this->rating}";

            $headers = get_headers($url, 1);
            $signup = false;

            if (strpos($headers[0], '404')) {

                $signup = true;
                $url = str_replace("&d={$this->imageset}", "&d=mm", $url);
            }

            if ($this->img_tag) {

                if ($signup === true)
                    $tag = '<a href="'.$this->gravatar_signup.$this->email.'" target="_blank" title="Click here and signup for create your gravatar">';
                else
                    $tag = '';

                $tag .= '<img src="' . $url . '"';

                foreach ($this->img_tag_attr as $key => $val)
                    $tag .= ' ' . $key . '="' . $val . '"';

                $tag .= ' />';
                
                $tag .= $signup === true ? '</a> '.$label : $label;
            }

            return isset($tag) ? $tag : $url;
        }

        return false;
    }

    private function isEmail($email) {

        return preg_match("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^", trim($email));
    }

    private function setError($message) {

        $this->error['error'] = true;
        $this->error['message'] = $message;
    }

    public function isError() {

        return $this->error['error'];
    }

    public function getError() {

        return $this->error['message'];
    }

}

?>
