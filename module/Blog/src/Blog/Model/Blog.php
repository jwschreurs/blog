<?php

namespace Blog\Model;
 
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
 
class Blog implements InputFilterAwareInterface
{
    public $title;
 	public $text;
	public $image;
	public $movie;
    protected $inputFilter;
	 
    public function exchangeArray($data)
    {
        $this->title  = (isset($data['title']))  ? $data['title']     : null; 
		$this->text   = (isset($data['text']))   ? $data['text']      : null; 
        $this->fileupload  = (isset($data['fileupload']))  ? $data['fileupload']     : null;
		
		//$this->movie  = (isset($data['movie']))  ? $data['movie']     : null; 
    } 
     
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }
     
    public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();
              
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'Title',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min'      => 1,
                                'max'      => 100,
                            ),
                        ),
                    ),
                ))
            );
			
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'Text',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min'      => 1,
                                'max'      => 2500,
                            ),
                        ),
                    ),
                ))
            );
			  $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'Image',
                ))
            );
            // $inputFilter->add(
			// array(
			    // 'type'       => 'Zend\InputFilter\FileInput',
			    // 'name'       => 'Image',
			    // 'required'   => false,
			    // 'filters'    => array(
			        // array('name' => 'filerename', 'options' => array(
			            // 'target' => './data/tmpuploads/file',
			            // 'randomize' => true,
			        // )),
			        // array('name' => 'filelowercase'),
			    // ),
			    // 'validators' => array(
			        // array('name' => 'Image'),
			    // ),
			// ));
			/*
            $inputFilter->add(
                $factory->createInput(array(
                    'name'     => 'movie',
                    'required' => true,
                    'filters'  => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                    ),
                    'validators' => array(
                        array(
                            'name'    => 'StringLength',
                            'options' => array(
                                'encoding' => 'UTF-8',
                                'min'      => 1,
                                'max'      => 100,
                            ),
                        ),
                    ),
                ))
            );
			 */
            $this->inputFilter = $inputFilter;
        }
         
        return $this->inputFilter;
    }
}