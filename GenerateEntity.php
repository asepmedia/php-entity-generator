<?php

class GenerateEntity
{
  public $className = '';

  public $setterGetter = '';

  public $properties = [];

  public $path = '';

  public function setProperties($properties)
  {
    $this->properties  = $properties;
    return $this;
  }

  public function setClassName($className)
  {
    $this->className  = $className;
    return $this;
  }

  public function setPath($path)
  {
    $this->path  = $path;
    return $this;
  }

  public function generateProperty()
  {
    $property = '';

    foreach($this->properties as $key => $value) {
      if($key > 0) {
        $property .= "\n\t";
      }
      $property .= 'public $'.$value.';'."\n";
      $this->generateSetterGetter($key, $value);
    }

    return $property;
  }

  public function generateSetterGetter($key, $value)
  {
    if($key > 0) {
      $this->setterGetter .= "\n\t";
    }

    $this->setterGetter .= 'public function set'.ucfirst($value).'($'.$value.')
  {
    $this->'.$value.' = $'.$value.';
    return $this;
  }'."\n".'
  public function get'.ucfirst($value).'()
  {
    return $this->'.$value.';
  }'."\n";
  }

  public function build()
  {
    $sampleFile = file_get_contents('SampleEntity.txt');
    $sampleFile = str_replace('#classname', $this->className, $sampleFile);
    $sampleFile = str_replace('#property', $this->generateProperty(), $sampleFile);
    $sampleFile = str_replace('#setterGetter', $this->setterGetter, $sampleFile);

    file_put_contents($this->path != '' ? $this->path : ''."{$this->className}.php", $sampleFile);
  }
}


$generate = (new GenerateEntity())
  ->setPath('')
  ->setClassName('ProductEntity')
  ->setProperties([
    'id',
    'title',
    'price',
    'image',
    'description',
    'stock',
    'isAvailable'
  ])
  ->build();

?>