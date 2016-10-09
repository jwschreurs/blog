<?php
 
namespace Blog\Entity;
 
use Doctrine\ORM\Mapping as ORM;
 
/**
 * Track
 *
 * @ORM\Table(name="blog")
 * @ORM\Entity
 */
class Blog
{
   /**
    * @ORM\Id
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id;
	/** @ORM\Column(type="string") */
    private $title;
	/** @ORM\Column(type="string") */
 	private $text;
	/** @ORM\Column(type="string") */
	private $image;
	/** @ORM\Column(type="string") */
	private $movie;
 
 
    
    public function getId()
    {
        return $this->id;
    }
 
    
    public function setTitle($title)
    {
        $this->title = $title;
     
        return $this;
    }
 
    
    public function getTitle()
    {
        return $this->title;
    }
 
 
    public function setText($text)
    {
        $this->text = $title;
     
        return $this;
    }
 
    public function getText()
    {
        return $this->text;
    }
	
	public function setImage($image)
    {
        $this->image = $image;
     
        return $this;
    }
 
    public function getImage()
    {
        return $this->image;
    }
	
	public function setMovie($movie)
    {
        $this->movie = $movie;
     
        return $this;
    }
 
    public function getMovie()
    {
        return $this->movie;
    }
	public function truncate($text, $length = 100, $options = array())
    {
        $default = array(
            'ending' => '...', 'exact' => false
        );
        $options = array_merge($default, $options);
        extract($options);

        if (mb_strlen($text) <= $length) {
            return $text;
        } else {
            $truncate = mb_substr($text, 0, $length - mb_strlen($ending));
        }

        if (!$exact) {
            $spacepos = mb_strrpos($truncate, ' ');
            if (isset($spacepos)) {
                $truncate = mb_substr($truncate, 0, $spacepos);
            }
        }
        $truncate .= $ending;
        return $truncate;
    }
    
}